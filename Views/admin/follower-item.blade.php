@if(!empty($items) && (!$items->isEmpty()) )
<?php
    $withs = [
        'order' => '5%',
        'name' => '20%',
        'email' => '20%',
        'updated_at' => '20%',
        'created_at' => '20%',
        'operations' => '5%',
        'status' => '5%',
        'delete' => '5%',
    ];

    global $counter;
    $nav = $items->toArray();
    $counter = ($nav['current_page'] - 1) * $nav['per_page'] + 1;
?>
<caption>
    @if($nav['total'] == 1)
        {!! trans($plang_admin.'.descriptions.counter', ['number' => $nav['total']]) !!}
    @else
        {!! trans($plang_admin.'.descriptions.counters', ['number' => $nav['total']]) !!}
    @endif
</caption>

<table class="table table-hover" >

    <thead>
        <tr style="height: 50px;">

            <!--ORDER-->
            <th style='width:{{ $withs['order'] }}'>
                {{ trans($plang_admin.'.columns.order') }}
            </th>

            <!-- NAME -->
            <?php $name = 'user_following_name' ?>

            <th class="hidden-xs" style='width:{{ $withs['name'] }}'>{!! trans($plang_admin.'.columns.follow_name') !!}
                <a href='{!! $sorting["url"][$name] !!}' class='tb-id' data-order='asc'>
                    @if($sorting['items'][$name] == 'asc')
                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                    @elseif($sorting['items'][$name] == 'desc')
                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </a>
            </th>

                        <!-- EMAIL -->
            <?php $name = 'user_following_email' ?>

            <th class="hidden-xs" style='width:{{ $withs['email'] }}'>{!! trans($plang_admin.'.columns.follow_email') !!}
                <a href='{!! $sorting["url"][$name] !!}' class='tb-id' data-order='asc'>
                    @if($sorting['items'][$name] == 'asc')
                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                    @elseif($sorting['items'][$name] == 'desc')
                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </a>
            </th>

            <?php $name = 'created_at' ?>

            <th class="hidden-xs" style='width:{{ $withs['created_at'] }}'>{!! trans($plang_admin.'.columns.created_at') !!}
                <a href='{!! $sorting["url"][$name] !!}' class='tb-id' data-order='asc'>
                    @if($sorting['items'][$name] == 'asc')
                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                    @elseif($sorting['items'][$name] == 'desc')
                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </a>
            </th>
            <!-- NAME -->
            <?php $name = 'updated_at' ?>

            <th class="hidden-xs" style='width:{{ $withs['updated_at'] }}'>{!! trans($plang_admin.'.columns.updated_at') !!}
                <a href='{!! $sorting["url"][$name] !!}' class='tb-id' data-order='asc'>
                    @if($sorting['items'][$name] == 'asc')
                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                    @elseif($sorting['items'][$name] == 'desc')
                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </a>
            </th>

            <!--REF-->
            <?php $name = 'follower_status' ?>

            <th class="hidden-xs" style='width:{{ $withs['status'] }}'>{!! trans($plang_admin.'.columns.follower_status') !!}
                <a href='{!! $sorting["url"][$name] !!}' class='tb-id' data-order='asc'>
                    @if($sorting['items'][$name] == 'asc')
                        <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                    @elseif($sorting['items'][$name] == 'desc')
                        <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    @endif
                </a>
            </th>

            <!--OPERATIONS-->
            <th style='width:{{ $withs['operations'] }}'>
                <span class='lb-delete-all'>
                    {{ trans($plang_admin.'.columns.operations') }}
                </span>

                {!! Form::submit(trans($plang_admin.'.buttons.delete'), array("class"=>"btn btn-danger pull-right delete btn-delete-all del-trash", 'name'=>'del-trash')) !!}
                {!! Form::submit(trans($plang_admin.'.buttons.delete'), array("class"=>"btn btn-warning pull-right delete btn-delete-all del-forever", 'name'=>'del-forever')) !!}
            </th>

            <!--DELETE-->
            <th style='width:{{ $withs['delete'] }}'>
                <span class="del-checkbox pull-right">
                    <input type="checkbox" id="selecctall" />
                    <label for="del-checkbox"></label>
                </span>
            </th>

        </tr>

    </thead>

    <tbody>
        @foreach($items as $item)
            <tr>
                <!--COUNTER-->
                <td> <?php echo $counter; $counter++ ?> </td>

                <!--NAME-->
                <td> {!! $item->first_name !!} </td>
                <!--NAME-->
                <td> {!! $item->email !!} </td>
                <!--UPDATED AT-->
                <td> {!! $item->created_at !!} </td>
                <!--UPDATED AT-->
                <td> {!! $item->updated_at !!} </td>
                <!--STATUS-->
                <td>{!! $item->activated ? '<i class="fa fa-circle green"></i>' : '<i class="fa fa-circle-o red"></i>' !!}</td>
                <!--OPERATOR-->
                <td>
                    <!--delete-->
                    <a href="{!! URL::route('followers.delete',[  'id' => $item->user_following_id,
                                                                '_token' => csrf_token(),
                                                              ])
                             !!}"
                       class="margin-left-5 delete">
                        <i class="fa fa-minus-circle red"></i>
                    </a>

                </td>

                <!--DELETE-->
                <td>
                    <span class='box-item pull-right'>
                        <input type="checkbox" id="<?php echo $item->id ?>" name="ids[]" value="{!! $item->id !!}">
                        <label for="box-item"></label>
                    </span>
                </td>

            </tr>
        @endforeach

    </tbody>

</table>
<div class="paginator">
    {!! $items->appends($request->except(['page']) )->render() !!}
</div>
@else
    <!--SEARCH RESULT MESSAGE-->
    <span class="text-warning">
        <h5>
            {{ trans($plang_admin.'.descriptions.not-found') }}
        </h5>
    </span>
    <!--/SEARCH RESULT MESSAGE-->
@endif

@section('footer_scripts')
    @parent
    {!! HTML::script('packages/foostart/package-follower/js/form-table.js')  !!}
@stop
<script src="http://training.local.com/packages/jacopo/laravel-authentication-acl/js/vendor/jquery-2.2.4.min.js"></script>
<script type="text/javascript" language="javascript">
$(function() {
    $('#selecctall').change(function(){
        var checkboxes = $(this).closest('form').find(':checkbox');
        if($(this).prop('checked')) {
            $(".btn-delete-all").show();
            checkboxes.prop('checked', true);
        } else {
            checkboxes.prop('checked', false);
            $(".btn-delete-all").hide();
        }
    });
});
$(".btn-delete-all").hide();

</script>