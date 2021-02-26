@php
    $reactionLike = $reaction->where('is_like', \App\Models\Article::IS_LIKE)->pluck('user_id');
    $reactionUnLike = $reaction->where('is_like', \App\Models\Article::UN_LIKE)->pluck('user_id');
    $markTrust = $mark->where('is_trust', \App\Models\Article::IS_TRUST)->pluck('user_id');
    $markFake = $mark->where('is_trust', \App\Models\Article::IS_FAKE);
@endphp
<script src="https://kit.fontawesome.com/f7df2f0fc6.js" crossorigin="anonymous"></script>
<a class="btn btn-primary" data-toggle="modal" data-target="#modalLike">Like : {{$reactionLike->count()}}</a>


<div class="modal fade" id="modalLike" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Danh sách những user like <i class="far fa-thumbs-up"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(!$reactionLike->isEmpty())
            <div class="modal-body">
                @foreach(\App\Models\Author::whereIn('id', $reactionLike)->get() as $author)
                    <p><i class="far fa-thumbs-up"></i> <a href=""> {{$author->name}}</a></p>
                @endforeach
            </div>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<a class="btn btn-danger" data-toggle="modal" data-target="#modalUnLike">Dislike: {{$reactionUnLike->count()}}</a>

<div class="modal fade" id="modalUnLike" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Danh sách những user dislike</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(!$reactionUnLike->isEmpty())
                <div class="modal-body">
                    @foreach(\App\Models\Author::whereIn('id', $reactionUnLike)->get() as $author)
                        <a href="/admin/author/{{$author->id}}/show"><i class="far fa-thumbs-down"></i>{{ $author->name}}</a>
                    @endforeach
                </div>
            @endif
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
<a class="btn btn-info" data-toggle="modal" data-target="#modalTrust">Trust: {{$markTrust->count()}}</a>

<div class="modal fade" id="modalTrust" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Danh sách những user trust</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(!$markTrust->isEmpty())
                <div class="modal-body">
                    @foreach(\App\Models\Author::whereIn('id', $markTrust)->get() as $author)
                        <p><a href=""><i class="fas fa-heart"></i> {{ $author->name}}</a></p>
                    @endforeach
                </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<a class="btn btn-warning" data-toggle="modal" data-target="#modalFake">Fake: {{$markFake->pluck('user_id')->count()}}</a>
<div class="modal fade" id="modalFake" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Danh sách những user fake <i class="far fa-copyright"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(!$markFake->pluck('user_id')->isEmpty())
                <div class="modal-body">
                    @foreach(\App\Models\Author::whereIn('id', $markFake->pluck('user_id'))->get() as $author)
                        <div class="evident">
                            <p>Member :<a href="">{{ $author->name}}</a></p>
                            @php
                                $evident = json_decode($markFake->get()->evident, true);
                            @endphp
                            <p>Link :<a href="">{{$evident['link']}}</a></p>
                            <p>Image : <a href="">{{$evident['image']}}</a></p>
                            <p>Video : <a href="">{{$evident['video']}}</a></p>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-backdrop{
        position: unset;
    }
</style>
