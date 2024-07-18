{{-- @extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable')) --}}


@extends('layouts.error')

@section('title', __('Service Unaivailable'))  
@section('code', '429')
@section('title message', __('Service Unaivailable'))
@section('message', __('The service is currently unavailable. Please try again later.'))