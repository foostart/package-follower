<div class="panel panel-info">
    <!--HEADING-->
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin">
            <i class="fa fa-user"></i>
            {!! $request->all() ? trans('jacopo-admin.users-search') : trans('jacopo-admin.sidebars.users-list') !!}
        </h3>
    </div>

    <div class="panel-body">

        <!--TOP MENU-->
        <div class="row">
            <div class="col-lg-10 col-md-8 col-sm-8">
            </div>

        </div>

        <!--TABLE-->
        <div class="row">
            <div class="col-md-12">
                @if(! $users->isEmpty() )
                <div class="table-responsive">

<?php
    $withs = [
        'order' => '5%',
        'first_name' => '20%',
        'email' => '20%',
        'last_name' => '20%',
        'follower_status' => '10%',
        'last_login' => '20%',
        'delete' => '5%',
    ];

?>

<table class="table table-hover">

    <thead>
        <tr style="height: 50px;">

            <!--ORDER-->
            <th style='width:{{ $withs['order'] }}'>
                {{ trans($plang_admin.'.columns.order') }}
            </th>

                                <!-- EMAIL -->
            <?php $name = 'email' ?>

            <th class="hidden-xs" style='width:{{ $withs['email'] }}'>{!! trans($plang_admin.'.columns.email') !!}
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
            <?php $name = 'first_name' ?>

            <th class="hidden-xs" style='width:{{ $withs['first_name'] }}'>{!! trans($plang_admin.'.columns.first_name') !!}
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

            <?php $name = 'last_name' ?>

            <th class="hidden-xs" style='width:{{ $withs['last_name'] }}'>{!! trans($plang_admin.'.columns.last_name') !!}
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
            <?php $name = 'follower_status' ?>

            <th class="hidden-xs" style='width:{{ $withs['follower_status'] }}'>{!! trans($plang_admin.'.columns.follower_status') !!}
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
            <?php $name = 'last_login' ?>

            <th class="hidden-xs" style='width:{{ $withs['last_login'] }}'>{!! trans($plang_admin.'.columns.last_login') !!}
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

                                <!-- OPERATION -->
                                <th>{!! trans('jacopo-admin.menu.operations') !!}</th>
                            </tr>
                        </thead>
                        
                        <!--DATA-->
                        <tbody>
                            <?php
                            $index = $users->perPage() * ($users->currentPage() - 1) + 1;
                            ?>
                            
                            @foreach($users as $user)
                            <form method="POST" action="{!! URL::route('followers.postadd',['_token' => csrf_token()]) !!}" id="formName">
                            <tr>
                                <td>{!! $user->id !!}</td>
                                <td>{!! $user->email !!}</td>
                                <td class="hidden-xs">{!! $user->first_name !!}</td>
                                <td class="hidden-xs">{!! $user->last_name !!}</td>
                                <td >{!! $user->activated ? '<i class="fa fa-circle green"></i>' : '<i class="fa fa-circle-o red"></i>' !!}</td>
                                <td class="hidden-xs">{!! $user->last_login ? $user->last_login : trans('jacopo-admin.messages.message-last-login') !!}</td>
                                <td style="text-align: center;">
                                    <input type="hidden" name="user_following_id" value="{!! $user->id !!}">
                                    @if(!isset($user->follow_flag))
                                    <button type="submit" class="fa fa-plus-circle light-blue"></button>
                                    @else
                                    <button type="submit" class="fa fa-minus-circle red"></button>
                                    @endif
                                </td>
                            </tr>
                            </form>
                        </tbody>
                        @endforeach
                        
                    </table>
                </div>
                <div class="paginator">
                    {!! $users->appends($request->except(['page']) )->render() !!}
                </div>
                @else
                <span class="text-warning"><h5>{!! trans('jacopo-admin.messages.empty-data') !!}</h5></span>
                @endif
            </div>
        </div>
    </div>
</div>
