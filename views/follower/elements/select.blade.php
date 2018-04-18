<!-- CATEGORY LIST -->
<div class="form-group">
    <?php $follower_name = $request->get('follower_titlename') ? $request->get('follower_name') : @$follower->follower_name ?>

    {!! Form::label('category_id', trans('follower::follower_admin.follower_category_name').':') !!}

    {!! Form::select('category_id', @$categories, @$follower, ['class' => 'form-control']) !!}
</div>
<!-- /CATEGORY LIST -->