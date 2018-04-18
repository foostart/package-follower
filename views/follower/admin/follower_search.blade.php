
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-search"></i><?php echo trans('follower::follower_admin.page_search') ?></h3>
    </div>
    <div class="panel-body">

        {!! Form::open(['route' => 'admin_follower','method' => 'get']) !!}

        <!--TITLE-->
        <div class="form-group">
            {!! Form::label('follower_name', trans('follower::follower_admin.follower_name_label')) !!}
            {!! Form::text('follower_name', @$params['follower_name'], ['class' => 'form-control', 'placeholder' => trans('follower::follower_admin.follower_name_placeholder')]) !!}
        </div>
        <!--/END TITLE-->

        {!! Form::submit(trans('follower::follower_admin.search').'', ["class" => "btn btn-info pull-right"]) !!}
        {!! Form::close() !!}
    </div>
</div>


