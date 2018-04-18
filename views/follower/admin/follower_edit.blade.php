@extends('laravel-authentication-acl::admin.layouts.base-2cols')

@section('title')
Admin area: {{ trans('follower::follower_admin.page_edit') }}
@stop
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title bariol-thin">
                        {!! !empty($follower->follower_id) ? '<i class="fa fa-pencil"></i>'.trans('follower::follower_admin.form_edit') : '<i class="fa fa-users"></i>'.trans('follower::follower_admin.form_add') !!}
                    </h3>
                </div>

                {{-- model general errors from the form --}}
                @if($errors->has('follower_name') )
                <div class="alert alert-danger">{!! $errors->first('follower_name') !!}</div>
                @endif

                @if($errors->has('name_unvalid_length') )
                <div class="alert alert-danger">{!! $errors->first('name_unvalid_length') !!}</div>
                @endif

                {{-- successful message --}}
                <?php $message = Session::get('message'); ?>
                @if( isset($message) )
                <div class="alert alert-success">{{$message}}</div>
                @endif

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h4>{!! trans('follower::follower_admin.form_heading') !!}</h4>
                            {!! Form::open(['route'=>['admin_follower.post', 'id' => @$follower->follower_id],  'files'=>true, 'method' => 'post'])  !!}



                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#home">
                                        {!! trans('follower::follower_admin.tab_overview') !!}
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1">
                                        {!! trans('follower::follower_admin.tab_attributes') !!}
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <!--TEMPLATE OVERVIEW-->
                                <div id="home" class="tab-pane fade in active">
                                    <!-- follower NAME TEXT-->
                                    @include('follower::follower.elements.text', ['name' => 'follower_name'])
                                    <!-- /END follower NAME TEXT -->
                                </div>

                                <!--TEMPLATE ATTRIBUTES-->
                                <div id="menu1" class="tab-pane fade">
                                    <!-- follower CATEGORIES TEXT-->
                                    @include('follower::follower.elements.select', ['name' => 'follower_category_name'])
                                    <!-- /END follower CATEGORIES TEXT-->
                                </div>

                            </div>





                            {!! Form::hidden('id',@$follower->follower_id) !!}

                            <!-- DELETE BUTTON -->
                            <a href="{!! URL::route('admin_follower.delete',['id' => @$follower->follower_id, '_token' => csrf_token()]) !!}"
                               class="btn btn-danger pull-right margin-left-5 delete">
                                Delete
                            </a>
                            <!-- DELETE BUTTON -->

                            <!-- SAVE BUTTON -->
                            {!! Form::submit('Save', array("class"=>"btn btn-info pull-right ")) !!}
                            <!-- /SAVE BUTTON -->

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='col-md-4'>
            @include('follower::follower.admin.follower_search')
        </div>

    </div>
</div>
@stop