@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Internal Server Error Occured. Please Try Again Later'))
