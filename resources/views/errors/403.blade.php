{{-- @extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden')) --}}

@extends('layouts.error')

@section('title', __('Forbidden'))  
@section('code', '403')
@section('title message', __('Forbidden'))
@section('message',__('You Don\'t Have Permission To Access This Page.'))
    



