<?php

if ($this->session->flashdata('success_msg') != ''):
    echo '<div class="alert alert-block alert-success fade in"><button type="button" class="close close-sm" data-dismiss="alert"></button>
			   ' . $this->session->flashdata('success_msg') . '</div>';
endif;

if ($this->session->flashdata('message') != ''):
    echo '<div class="alert alert-block alert-success fade in"><button type="button" class="close close-sm" data-dismiss="alert"></button>
			   ' . $this->session->flashdata('message') . '</div>';
endif;

if ($this->session->flashdata('error_msg') != ''):
    echo '<div class="alert alert-block alert-danger fade in"><button type="button" class="close close-sm" data-dismiss="alert"></button>
			   ' . $this->session->flashdata('error_msg') . '</div>';
endif;
?>