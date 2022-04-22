@extends('layouts.app')

@section('content')
<a href='{{ route('home')}}'>[ home ]</a>
<form id='updateItemTypeForm' method="POST" action="{{ route('itemTypes.store') }}" class=' mt-3 mb-3'>
    @csrf
    <input type='hidden' id='itemTypeIDInput'>
  <div>
    Item Name:
  </div><div>
    <input id='itemTypeNameInput' type='text' name='name' maxlength=64>
    <input type='checkbox' name='tool'> Tool?
    <input type='checkbox' name='countable' checked> Countable?

  </div><div>
    Description
  </div><div>
    <textarea id='itemTypeDescriptionInput' name='description' class='form-control'></textarea>
  </div><div>
    <button id='itemTypeSubmit' >create</button>
  </div>

</form>
  @foreach ($itemTypes as $itemType)
    <div class='fw-bold'>
      #{{$itemType->id}} <span id='itemName{{ $itemType->id }}'>{{ $itemType->name }}</span>
      @if($itemType->material != null)
      ( {{ $itemType->material }} / {{ $itemType->durability }} )
      @endif
      <button id='updateItemType-{{$itemType->id}}' class='updateItemType btn btn-link'>[ update ]</button>
    </div><div id='itemDescription{{ $itemType->id }}'>
      {{ $itemType->description }}
    </div>

  @endforeach


@endsection
