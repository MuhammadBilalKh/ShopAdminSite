/* ============================================================
   ShopZone – Seed / Demo Data
   Runs once on first load to populate localStorage
   ============================================================ */

function seedData() {
  /* ── Skip if already seeded ── */
  if (localStorage.getItem('sz_seeded')) return;

  /* ── Categories ── */
  const categories = [
    { id:'cat_1', name:'Electronics',   icon:'ri-smartphone-line',  color:'#6c63ff', description:'Latest gadgets & tech', active:true },
    { id:'cat_2', name:'Fashion',        icon:'ri-t-shirt-line',     color:'#ff6584', description:'Trendy clothing & accessories', active:true },
    { id:'cat_3', name:'Home & Living',  icon:'ri-home-heart-line',  color:'#43c6ac', description:'Furniture & decor', active:true },
    { id:'cat_4', name:'Sports',         icon:'ri-run-line',         color:'#f59e0b', description:'Sports & fitness gear', active:true },
    { id:'cat_5', name:'Beauty',         icon:'ri-magic-line',       color:'#ec4899', description:'Skincare & cosmetics', active:true },
    { id:'cat_6', name:'Books',          icon:'ri-book-open-line',   color:'#3b82f6', description:'Books & stationery', active:true },
  ];
  _set('sz_categories', categories);

  /* ── Products ── */
  const products = [
    /* Electronics */
    { id:'p_1',  name:'Wireless Noise-Cancelling Headphones', category:'Electronics', price:149.99, salePrice:119.99, stock:25, rating:4.8, image:'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', description:'Premium sound quality with 30-hour battery life and active noise cancellation.', featured:true,  isNew:false, tags:['headphones','wireless','audio'] },
    { id:'p_2',  name:'Smart Watch Series X',                 category:'Electronics', price:299.99, salePrice:249.99, stock:15, rating:4.6, image:'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop', description:'Track fitness, receive notifications, and more on your wrist.', featured:true,  isNew:true,  tags:['smartwatch','wearable'] },
    { id:'p_3',  name:'Portable Bluetooth Speaker',           category:'Electronics', price:89.99,  salePrice:null,   stock:40, rating:4.5, image:'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=400&fit=crop', description:'360° surround sound with 12-hour playback and waterproof design.', featured:false, isNew:true,  tags:['speaker','bluetooth'] },
    { id:'p_4',  name:'4K Ultra HD Action Camera',            category:'Electronics', price:199.99, salePrice:169.99, stock:18, rating:4.7, image:'https://images.unsplash.com/photo-1495121553079-4c61bcce1894?w=400&h=400&fit=crop', description:'Capture every adventure in stunning 4K quality.', featured:true,  isNew:false, tags:['camera','action','4k'] },
    { id:'p_5',  name:'Mechanical Gaming Keyboard',           category:'Electronics', price:129.99, salePrice:99.99,  stock:30, rating:4.6, image:'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&h=400&fit=crop', description:'RGB backlit mechanical switches for the ultimate gaming experience.', featured:false, isNew:false, tags:['keyboard','gaming','rgb'] },
    { id:'p_6',  name:'USB-C Hub 7-in-1',                    category:'Electronics', price:49.99,  salePrice:39.99,  stock:60, rating:4.4, image:'https://images.unsplash.com/photo-1625315714641-9e2a6d8e2e65?w=400&h=400&fit=crop', description:'Expand your laptop with HDMI, USB 3.0, SD card reader and more.', featured:false, isNew:false, tags:['hub','usb-c','accessories'] },

    /* Fashion */
    { id:'p_7',  name:"Men's Classic Oxford Shirt",            category:'Fashion',     price:59.99,  salePrice:44.99,  stock:50, rating:4.3, image:'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&h=400&fit=crop', description:'Slim-fit premium cotton Oxford shirt for any occasion.', featured:false, isNew:true,  tags:['shirt','men','formal'] },
    { id:'p_8',  name:"Women's Summer Floral Dress",           category:'Fashion',     price:79.99,  salePrice:59.99,  stock:35, rating:4.7, image:'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&h=400&fit=crop', description:'Light floral print dress perfect for warm days.', featured:true,  isNew:true,  tags:['dress','women','summer'] },
    { id:'p_9',  name:'Running Sneakers Pro',                  category:'Fashion',     price:119.99, salePrice:89.99,  stock:28, rating:4.5, image:'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', description:'Lightweight, breathable running shoes for all terrains.', featured:true,  isNew:false, tags:['shoes','running','sports'] },
    { id:'p_10', name:'Leather Crossbody Bag',                 category:'Fashion',     price:89.99,  salePrice:null,   stock:22, rating:4.6, image:'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=400&fit=crop', description:'Genuine leather crossbody with multiple compartments.', featured:false, isNew:false, tags:['bag','leather','women'] },

    /* Home & Living */
    { id:'p_11', name:'Minimalist Desk Lamp',                  category:'Home & Living', price:45.99, salePrice:35.99, stock:40, rating:4.4, image:'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=400&fit=crop', description:'Adjustable LED desk lamp with touch dimming and USB charging port.', featured:false, isNew:true,  tags:['lamp','desk','led'] },
    { id:'p_12', name:'Ceramic Coffee Mug Set (4 pcs)',         category:'Home & Living', price:34.99, salePrice:27.99, stock:55, rating:4.5, image:'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=400&h=400&fit=crop', description:'Artisan ceramic mugs in 4 colors, microwave and dishwasher safe.', featured:false, isNew:false, tags:['mug','kitchen','gift'] },
    { id:'p_13', name:'Indoor Plant Pot Set',                   category:'Home & Living', price:28.99, salePrice:null,  stock:45, rating:4.3, image:'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=400&h=400&fit=crop', description:'Set of 3 geometric concrete pots for indoor plants.', featured:false, isNew:false, tags:['plants','decor','home'] },
    { id:'p_14', name:'Scented Candle Gift Set',                category:'Home & Living', price:39.99, salePrice:29.99, stock:33, rating:4.7, image:'https://images.unsplash.com/photo-1603905563571-ef4f28d3a065?w=400&h=400&fit=crop', description:'6 premium soy wax candles in relaxing scents.', featured:true,  isNew:false, tags:['candle','gift','home'] },

    /* Sports */
    { id:'p_15', name:'Yoga Mat Premium',                       category:'Sports', price:49.99, salePrice:39.99, stock:60, rating:4.6, image:'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=400&fit=crop', description:'Non-slip 6mm thick yoga mat with carrying strap.', featured:false, isNew:false, tags:['yoga','fitness','mat'] },
    { id:'p_16', name:'Adjustable Dumbbell Set',                category:'Sports', price:189.99, salePrice:149.99, stock:12, rating:4.8, image:'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=400&fit=crop', description:'5–52 lb adjustable dumbbells, replaces 15 sets of weights.', featured:true,  isNew:false, tags:['dumbbell','weights','gym'] },
    { id:'p_17', name:'Resistance Bands Set',                   category:'Sports', price:24.99, salePrice:null,  stock:80, rating:4.4, image:'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=400&h=400&fit=crop', description:'5-level resistance band set for full body workouts.', featured:false, isNew:true,  tags:['bands','fitness','workout'] },

    /* Beauty */
    { id:'p_18', name:'Vitamin C Serum',                        category:'Beauty', price:38.99, salePrice:29.99, stock:70, rating:4.7, image:'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop', description:'Brightening Vitamin C serum with hyaluronic acid and antioxidants.', featured:false, isNew:false, tags:['serum','skincare','vitamin c'] },
    { id:'p_19', name:'Makeup Brush Set (12 pcs)',               category:'Beauty', price:29.99, salePrice:22.99, stock:55, rating:4.5, image:'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=400&h=400&fit=crop', description:'Professional synthetic brushes for flawless application.', featured:false, isNew:false, tags:['makeup','brushes','beauty'] },

    /* Books */
    { id:'p_20', name:'The Art of Clean Code',                  category:'Books', price:32.99, salePrice:24.99, stock:100, rating:4.9, image:'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=400&fit=crop', description:'A practical guide to writing beautiful, maintainable code.', featured:false, isNew:true,  tags:['programming','coding','books'] },
  ];
  _set('sz_products', products);

  /* ── Admin User ── */
  const existingUsers = _get('sz_users') || [];
  const adminExists = existingUsers.find(u => u.email === 'admin@shopzone.com');
  if (!adminExists) {
    existingUsers.unshift({
      id: 'u_admin', name: 'Admin User', email: 'admin@shopzone.com',
      password: 'Admin@123', role: 'admin', active: true,
      createdAt: new Date().toISOString(), phone: '+1 555-0100',
    });
  }

  /* ── Demo Customer ── */
  const demoExists = existingUsers.find(u => u.email === 'john@example.com');
  if (!demoExists) {
    existingUsers.push({
      id: 'u_demo', name: 'John Smith', email: 'john@example.com',
      password: 'Demo@123', role: 'customer', active: true,
      createdAt: new Date().toISOString(), phone: '+1 555-0199',
      address: '123 Main St, New York, NY 10001',
    });
  }
  _set('sz_users', existingUsers);

  /* ── Demo Orders ── */
  const orders = [
    {
      id: 'ORD-001', email: 'john@example.com', name: 'John Smith',
      phone: '+1 555-0199', address: '123 Main St, New York, NY 10001',
      items: [
        { name: 'Wireless Noise-Cancelling Headphones', qty: 1, finalPrice: 119.99, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=80&h=80&fit=crop' },
        { name: 'Smart Watch Series X', qty: 1, finalPrice: 249.99, image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=80&h=80&fit=crop' },
      ],
      subtotal: 369.98, shipping: 0, tax: 29.60, total: 399.58,
      paymentMethod: 'Credit Card', status: 'delivered',
      createdAt: new Date(Date.now() - 7 * 86400000).toISOString(),
      timeline: [
        { status: 'Order Placed',  date: new Date(Date.now() - 7*86400000).toISOString(), done: true },
        { status: 'Processing',    date: new Date(Date.now() - 6*86400000).toISOString(), done: true },
        { status: 'Shipped',       date: new Date(Date.now() - 4*86400000).toISOString(), done: true },
        { status: 'Delivered',     date: new Date(Date.now() - 2*86400000).toISOString(), done: true },
      ],
    },
    {
      id: 'ORD-002', email: 'john@example.com', name: 'John Smith',
      phone: '+1 555-0199', address: '123 Main St, New York, NY 10001',
      items: [
        { name: "Women's Summer Floral Dress", qty: 2, finalPrice: 59.99, image: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=80&h=80&fit=crop' },
      ],
      subtotal: 119.98, shipping: 0, tax: 9.60, total: 129.58,
      paymentMethod: 'PayPal', status: 'shipped',
      createdAt: new Date(Date.now() - 2 * 86400000).toISOString(),
      timeline: [
        { status: 'Order Placed', date: new Date(Date.now() - 2*86400000).toISOString(), done: true },
        { status: 'Processing',   date: new Date(Date.now() - 1*86400000).toISOString(), done: true },
        { status: 'Shipped',      date: new Date(Date.now() - 6*3600000).toISOString(),  done: true },
      ],
    },
    {
      id: 'ORD-003', email: 'demo2@example.com', name: 'Sarah Connor',
      phone: '+1 555-0200', address: '456 Oak Ave, Los Angeles, CA 90001',
      items: [
        { name: 'Adjustable Dumbbell Set', qty: 1, finalPrice: 149.99, image: 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=80&h=80&fit=crop' },
        { name: 'Yoga Mat Premium', qty: 1, finalPrice: 39.99, image: 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=80&h=80&fit=crop' },
      ],
      subtotal: 189.98, shipping: 9.99, tax: 15.20, total: 215.17,
      paymentMethod: 'Credit Card', status: 'processing',
      createdAt: new Date(Date.now() - 1 * 86400000).toISOString(),
      timeline: [
        { status: 'Order Placed', date: new Date(Date.now() - 1*86400000).toISOString(), done: true },
        { status: 'Processing',   date: new Date(Date.now() - 12*3600000).toISOString(), done: true },
      ],
    },
  ];
  _set('sz_orders', orders);

  /* ── Settings ── */
  _set('sz_settings', { storeName: 'ShopZone', currency: '$', taxRate: 0.08, shippingFee: 9.99, freeShippingOver: 50 });

  localStorage.setItem('sz_seeded', '1');
  console.log('[ShopZone] Seed data loaded.');
}

/* Auto-run on DOM ready */
$(document).ready(function() { seedData(); });
