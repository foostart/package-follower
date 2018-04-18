
@if( ! $followers->isEmpty() )
<table class="table table-hover">
    <thead>
        <tr>
            <td style='width:5%'>{{ trans('follower::follower_admin.order') }}</td>
            <th style='width:10%'>Follower ID</th>
            <th style='width:50%'>Follower title</th>
            <th style='width:20%'>{{ trans('follower::follower_admin.operations') }}</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $nav = $followers->toArray();
            $counter = ($nav['current_page'] - 1) * $nav['per_page'] + 1;
        ?>
        @foreach($followers as $follower)
        <tr>
            <td>
                <?php echo $counter; $counter++ ?>
            </td>
            <td>{!! $follower->follower_id !!}</td>
            <td>{!! $follower->follower_name !!}</td>
            <td>
                <a href="{!! URL::route('admin_follower.edit', ['id' => $follower->follower_id]) !!}"><i class="fa fa-edit fa-2x"></i></a>
                <a href="{!! URL::route('admin_follower.delete',['id' =>  $follower->follower_id, '_token' => csrf_token()]) !!}" class="margin-left-5 delete"><i class="fa fa-trash-o fa-2x"></i></a>
                <span class="clearfix"></span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
 <span class="text-warning">
	<h5>
		{{ trans('follower::follower_admin.message_find_failed') }}
	</h5>
 </span>
@endif
<div class="paginator">
    {!! $followers->appends($request->except(['page']) )->render() !!}
</div>