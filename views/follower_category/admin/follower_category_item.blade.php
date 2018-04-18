<!--ADD follower CATEGORY ITEM-->
<div class="row margin-bottom-12">
    <div class="col-md-12">
        <a href="{!! URL::route('admin_follower_category.edit') !!}" class="btn btn-info pull-right">
            <i class="fa fa-plus"></i>{{trans('follower::follower_admin.follower_category_add_button')}}
        </a>
    </div>
</div>
<!--/END ADD follower CATEGORY ITEM-->

@if( ! $followers_categories->isEmpty() )
<table class="table table-hover">
    <thead>
        <tr>
            <td style='width:5%'>
                {{ trans('follower::follower_admin.order') }}
            </td>

            <th style='width:10%'>
                {{ trans('follower::follower_admin.follower_categoty_id') }}
            </th>

            <th style='width:50%'>
                {{ trans('follower::follower_admin.follower_categoty_name') }}
            </th>

            <th style='width:20%'>
                {{ trans('follower::follower_admin.operations') }}
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $nav = $followers_categories->toArray();
            $counter = ($nav['current_page'] - 1) * $nav['per_page'] + 1;
        ?>
        @foreach($followers_categories as $follower_category)
        <tr>
            <!--COUNTER-->
            <td>
                <?php echo $counter; $counter++ ?>
            </td>
            <!--/END COUNTER-->

            <!--follower CATEGORY ID-->
            <td>
                {!! $follower_category->follower_category_id !!}
            </td>
            <!--/END follower CATEGORY ID-->

            <!--follower CATEGORY NAME-->
            <td>
                {!! $follower_category->follower_category_name !!}
            </td>
            <!--/END follower CATEGORY NAME-->

            <!--OPERATOR-->
            <td>
                <a href="{!! URL::route('admin_follower_category.edit', ['id' => $follower_category->follower_category_id]) !!}">
                    <i class="fa fa-edit fa-2x"></i>
                </a>
                <a href="{!! URL::route('admin_follower_category.delete',['id' =>  $follower_category->follower_category_id, '_token' => csrf_token()]) !!}"
                   class="margin-left-5 delete">
                    <i class="fa fa-trash-o fa-2x"></i>
                </a>
                <span class="clearfix"></span>
            </td>
            <!--/END OPERATOR-->
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <!-- FIND MESSAGE -->
    <span class="text-warning">
        <h5>
            {{ trans('follower::follower_admin.message_find_failed') }}
        </h5>
    </span>
    <!-- /END FIND MESSAGE -->
@endif
<div class="paginator">
    {!! $followers_categories->appends($request->except(['page']) )->render() !!}
</div>