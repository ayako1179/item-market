@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css')}}">
@endsection

@section('content')
<div class="address">
  <h1 class="address-title">住所の変更</h1>

  <form action="{{ route('orders.address.update', ['item_id' => $item->id]) }}" method="post" class="address-form">
    @csrf

    <div class="address-group">
      <label for="postal_code" class="group__label">郵便番号</label>
      <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address['postal_code']) }}">
      @error('postal_code')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <div class="address-group">
      <label for="address" class="group__label">住所</label>
      <input type="text" name="address" id="address" value="{{ old('address', $address['address']) }}">
      @error('address')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <div class="address-group">
      <label for="building" class="group__label">建物名</label>
      <input type="text" name="building" id="building" value="{{ old('building', $address['building']) }}">
    </div>

    <button type="submit" class="btn-primary">更新する</button>
  </form>
</div>
@endsection