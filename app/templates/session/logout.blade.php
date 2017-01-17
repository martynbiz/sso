<?php $this->layout('layouts/main'); ?>

<?php $this->section('content') ?>
    <div class="col-sm-6 col-sm-offset-3 sso-login">
        <div class="row">
        	<div class="col-xs-12">
                <form class="well text-center" method="post" action="/session">
                    <p>You are currently logged in to JapanTravel. Please click below to logout:</p>

                    <input type="hidden" name="_METHOD" value="DELETE">

                    <?php if(isset($params['returnTo'])): ?>
                        <input type="hidden" name="returnTo" value="<?= $params['returnTo'] ?>">
                    <?php endif; ?>

                    <button class="btn btn-default">Logout</button>
                </form>
            </div>
        </div>
    </div>
<?php $this->replace() ?>
