@if($entry->status == $status_pending)
<form action="{{route('article.updateStatus', ['id' => $entry->id])}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="status" value="{{$status_approve}}">
    <button type="submit" class="btn btn-primary" style="margin-right: 3px">Duyệt</button>
</form>
<form action="{{route('article.updateStatus', ['id' => $entry->id])}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="_token" value="{{$status_reject}}">
    <button type="submit" class="btn btn-danger">Không duyệt</button>
</form>
@endif
