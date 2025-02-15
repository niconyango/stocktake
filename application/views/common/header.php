<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <title>Portal | <?php echo $title; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- jQuery 3.7.1 -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- chart js  -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap 5.3.3 -->
    <link href="<?php echo base_url() ?>assets/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome-6-pro/css/all.min.css">
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- date picker -->
    <link href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <!-- select2-->
    <link href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
    <!-- DATA TABLES -->
    <link href="<?php echo base_url(); ?>assets/DataTables/datatables.min.css" rel="stylesheet"
          type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/DataTables/datatables.min.js"></script>
    <!-- fullCalendar 2.2.5-->
    <link href="<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet"
          type="text/css" media='print'/>
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/theme/css/AdminLTE.css" rel="stylesheet" type="text/css"/>

    <link href="<?php echo base_url(); ?>assets/theme/css/skins/skin-black.css" rel="stylesheet" type="text/css"/>
    <!-- Bootstrap 5.3.3 JS -->
    <script src="<?php echo base_url(); ?>assets/bootstrap-5.3.3/js/bootstrap.bundle.min.js"
            type="text/javascript"></script>
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
    <!-- Typeahead.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style type="text/css">
        html, body {
            font-size: 14px;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        body {
            font-size: 14px; /* Adjusts body text size */
        }

        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }

        .container {
            padding-top: 50px;
            margin: auto;
        }

        /* Box body should scroll if content overflows */
        .box-body {
            flex: 1;
            background: #f8f9fa; /* Optional background for distinction */
        }

        .main-content {
            flex: 1 0 auto;
        }

        /* Footer stays at the bottom */
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #343a40; /* Match Bootstrap dark footer */
            color: white;
            padding: 10px 0;
        }

        /* Floating message styles */
        .floating-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            background-color: green; /* Default color for success */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: none; /* Hidden by default */
        }

        .floating-message.error {
            background-color: red;
        }
    </style>
</head>