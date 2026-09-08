/* ============================================================
   ShopZone – localStorage Utility Layer
   All data access goes through this module.
   ============================================================ */

const DB = {
  PRODUCTS:   'sz_products',
  CATEGORIES: 'sz_categories',
  CART:       'sz_cart',
  WISHLIST:   'sz_wishlist',
  ORDERS:     'sz_orders',
  USERS:      'sz_users',
  CURRENT_USER:'sz_current_user',
  SETTINGS:   'sz_settings',
};

/* ── Generic helpers ── */
function _get(key)        { try { return JSON.parse(localStorage.getItem(key)) || null; } catch(e) { return null; } }
function _set(key, value) { localStorage.setItem(key, JSON.stringify(value)); }

/* ============================================================
   PRODUCTS
   ============================================================ */
const Products = {
  all()          { return _get(DB.PRODUCTS) || []; },
  save(arr)      { _set(DB.PRODUCTS, arr); },
  byId(id)       { return this.all().find(p => p.id === id) || null; },
  byCategory(cat){ return cat === 'all' ? this.all() : this.all().filter(p => p.category === cat); },
  add(product)   {
    const arr = this.all();
    product.id = 'p_' + Date.now();
    product.createdAt = new Date().toISOString();
    arr.unshift(product);
    this.save(arr);
    return product;
  },
  update(id, data){
    const arr = this.all().map(p => p.id === id ? { ...p, ...data, updatedAt: new Date().toISOString() } : p);
    this.save(arr);
  },
  delete(id){
    this.save(this.all().filter(p => p.id !== id));
  },
  search(query){
    const q = query.toLowerCase();
    return this.all().filter(p =>
      p.name.toLowerCase().includes(q) ||
      p.category.toLowerCase().includes(q) ||
      (p.description || '').toLowerCase().includes(q)
    );
  },
  featured(){ return this.all().filter(p => p.featured); },
  onSale()  { return this.all().filter(p => p.salePrice && p.salePrice < p.price); },
};

/* ============================================================
   CATEGORIES
   ============================================================ */
const Categories = {
  all()     { return _get(DB.CATEGORIES) || []; },
  save(arr) { _set(DB.CATEGORIES, arr); },
  byId(id)  { return this.all().find(c => c.id === id) || null; },
  add(cat)  {
    const arr = this.all();
    cat.id = 'cat_' + Date.now();
    cat.createdAt = new Date().toISOString();
    arr.push(cat);
    this.save(arr);
    return cat;
  },
  update(id, data){
    const arr = this.all().map(c => c.id === id ? { ...c, ...data } : c);
    this.save(arr);
  },
  delete(id){ this.save(this.all().filter(c => c.id !== id)); },
};

/* ============================================================
   CART
   ============================================================ */
const Cart = {
  get()           { return _get(DB.CART) || []; },
  save(arr)       { _set(DB.CART, arr); $(document).trigger('cart:updated'); },
  count()         { return this.get().reduce((s, i) => s + i.qty, 0); },
  total()         { return this.get().reduce((s, i) => s + (i.finalPrice * i.qty), 0); },
  subtotal()      { return this.total(); },
  add(product, qty = 1, options = {}){
    const cart = this.get();
    const key  = product.id + (options.size || '') + (options.color || '');
    const idx  = cart.findIndex(i => i.key === key);
    if (idx > -1) {
      cart[idx].qty += qty;
    } else {
      cart.push({
        key,
        productId: product.id,
        name:       product.name,
        image:      product.image,
        price:      product.price,
        finalPrice: product.salePrice || product.price,
        qty,
        options,
      });
    }
    this.save(cart);
  },
  remove(key)     { this.save(this.get().filter(i => i.key !== key)); },
  updateQty(key, qty){
    if (qty < 1) { this.remove(key); return; }
    this.save(this.get().map(i => i.key === key ? { ...i, qty } : i));
  },
  clear()         { this.save([]); },
};

/* ============================================================
   WISHLIST
   ============================================================ */
const Wishlist = {
  get()          { return _get(DB.WISHLIST) || []; },
  save(arr)      { _set(DB.WISHLIST, arr); $(document).trigger('wishlist:updated'); },
  has(id)        { return this.get().includes(id); },
  toggle(id){
    const list = this.get();
    const idx  = list.indexOf(id);
    if (idx > -1) list.splice(idx, 1); else list.push(id);
    this.save(list);
    return idx === -1; // returns true if added
  },
  count()        { return this.get().length; },
};

/* ============================================================
   ORDERS
   ============================================================ */
const Orders = {
  all()     { return _get(DB.ORDERS) || []; },
  save(arr) { _set(DB.ORDERS, arr); },
  byId(id)  { return this.all().find(o => o.id === id) || null; },
  byUser(email){ return this.all().filter(o => o.email === email); },
  create(orderData){
    const arr = this.all();
    const order = {
      ...orderData,
      id:        'ORD-' + Date.now(),
      createdAt: new Date().toISOString(),
      status:    'pending',
      timeline: [
        { status: 'Order Placed', date: new Date().toISOString(), done: true }
      ],
    };
    arr.unshift(order);
    this.save(arr);
    return order;
  },
  updateStatus(id, status){
    const statusLabels = {
      processing: 'Processing',
      shipped:    'Shipped',
      delivered:  'Delivered',
      cancelled:  'Cancelled',
    };
    const arr = this.all().map(o => {
      if (o.id !== id) return o;
      const timeline = [...(o.timeline || [])];
      if (statusLabels[status]) {
        timeline.push({ status: statusLabels[status], date: new Date().toISOString(), done: true });
      }
      return { ...o, status, timeline };
    });
    this.save(arr);
  },
  delete(id){ this.save(this.all().filter(o => o.id !== id)); },
  stats(){
    const all = this.all();
    return {
      total:     all.length,
      pending:   all.filter(o => o.status === 'pending').length,
      shipped:   all.filter(o => o.status === 'shipped').length,
      delivered: all.filter(o => o.status === 'delivered').length,
      cancelled: all.filter(o => o.status === 'cancelled').length,
      revenue:   all.filter(o => o.status !== 'cancelled').reduce((s, o) => s + (o.total || 0), 0),
    };
  },
};

/* ============================================================
   USERS / AUTH
   ============================================================ */
const Users = {
  all()     { return _get(DB.USERS) || []; },
  save(arr) { _set(DB.USERS, arr); },
  byEmail(email){ return this.all().find(u => u.email.toLowerCase() === email.toLowerCase()) || null; },
  byId(id)      { return this.all().find(u => u.id === id) || null; },
  register(data){
    if (this.byEmail(data.email)) return { success: false, msg: 'Email already registered.' };
    const arr = this.all();
    const user = {
      ...data,
      id:        'u_' + Date.now(),
      role:      'customer',
      createdAt: new Date().toISOString(),
      active:    true,
    };
    delete user.confirmPassword;
    arr.push(user);
    this.save(arr);
    return { success: true, user };
  },
  login(email, password){
    const user = this.byEmail(email);
    if (!user)                  return { success: false, msg: 'No account found with this email.' };
    if (user.password !== password) return { success: false, msg: 'Incorrect password.' };
    if (!user.active)           return { success: false, msg: 'Account is deactivated.' };
    _set(DB.CURRENT_USER, user);
    return { success: true, user };
  },
  logout(){ localStorage.removeItem(DB.CURRENT_USER); },
  current(){ return _get(DB.CURRENT_USER); },
  update(id, data){
    const arr = this.all().map(u => u.id === id ? { ...u, ...data } : u);
    this.save(arr);
    const cur = this.current();
    if (cur && cur.id === id) _set(DB.CURRENT_USER, { ...cur, ...data });
  },
  toggleActive(id){
    const user = this.byId(id);
    if (!user) return;
    this.update(id, { active: !user.active });
  },
  delete(id){ this.save(this.all().filter(u => u.id !== id)); },
};

/* ============================================================
   SETTINGS
   ============================================================ */
const Settings = {
  get()    { return _get(DB.SETTINGS) || { storeName: 'ShopZone', currency: '$', taxRate: 0.08, shippingFee: 9.99, freeShippingOver: 50 }; },
  save(s)  { _set(DB.SETTINGS, s); },
  update(data){ this.save({ ...this.get(), ...data }); },
};

/* ============================================================
   AUTH GUARD HELPERS
   ============================================================ */
function requireAuth(redirectTo = 'login.html') {
  if (!Users.current()) { window.location.href = redirectTo; return false; }
  return true;
}
function requireAdmin(redirectTo = '../login.html') {
  const u = Users.current();
  if (!u || u.role !== 'admin') { window.location.href = redirectTo; return false; }
  return true;
}
function isAdmin() {
  const u = Users.current();
  return u && u.role === 'admin';
}
