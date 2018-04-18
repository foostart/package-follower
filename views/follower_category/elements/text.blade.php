<!-- follower NAME -->
<div class="form-group">
    <?php $follower_category_name = $request->get('follower_titlename') ? $request->get('follower_name') : @$follower->follower_category_name ?>
    {!! Form::label($name, trans('follower::follower_admin.name').':') !!}
    {!! Form::text($name, $follower_category_name, ['class' => 'form-control', 'placeholder' => trans('follower::follower_admin.name').'']) !!}
</div>
<!-- /follower NAME -->