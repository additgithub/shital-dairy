</div>
<!-- END CONTAINER -->
</div>


<div class="modal auctions_models" id="fullfillment">
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <button class="cancel_bulk_fullfilment_button pull-right" type="button" data-dismiss="modal"> <i class="fa fa-times" aria-hidden="true"></i></button>
            <div class="clearfix"></div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- END CONTAINER -->

<script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->

<script src="<?php echo base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/breakpoints.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
<?php
if (isset($isyard) && $isyard == true) {
    echo '<script src="'.base_url().'assets/plugins/select2/select2.min.js" type="text/javascript"></script>';
}
else{
    echo '<script src="'.base_url().'assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>';
}
?>
<!-- <script src="<?php echo base_url(); ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script> -->
<!--<script src="<?php echo base_url(); ?>assets/js/core.js" type="text/javascript"></script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
			<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script> -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/lodash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/datatables.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/backbone-min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/demo/location-sel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/demo/theme-sel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery-notifications/js/demo/demo.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<!--Multi Select JS-->
<script src="<?php echo base_url(); ?>assets/plugins/multiselect/js/jquery.multi-select.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/notifications.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/alert/js/alert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/custom/js/custom-for-all.js" type="text/javascript"></script>


<?php
if (isset($extra_js) && is_array($extra_js) && !empty($extra_js)) {
    foreach ($extra_js as $js) {
        if (!empty($js)) {
            echo '<script src="' . base_url() . 'assets/custom/js/' . $js . '.js?' . randomNumber() . '" ></script>';
        }
    }
}
?>

</body>

</html>