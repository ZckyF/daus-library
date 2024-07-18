{{-- @extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message', __('Payment Required')) --}}


@extends('layouts.error')

@section('title', __('Payment Required'))  
@section('code', '402')
@section('title message', __('Payment Required'))
@section('message', __('The payment is required to access this resource.'))