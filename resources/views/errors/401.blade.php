{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}


@extends('layouts.error')

@section('title', __('Unauthorized'))  
@section('code', '401')
@section('title message', __('Unauthorized'))
@section('message',__('Unauthorized Access'))