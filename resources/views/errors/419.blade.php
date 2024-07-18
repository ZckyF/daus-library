{{-- @extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired')) --}}

@extends('layouts.error')

@section('title', __('Page Expired'))  
@section('code', '419')
@section('title message', __('Page Expired'))
@section('message', __('The page has expired. Please refresh the page and try again.'))