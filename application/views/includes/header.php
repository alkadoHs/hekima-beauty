<?php
$userId = $this->session->userdata("userId");


$username = $this->session->userdata("username");
$role = $this->session->userdata("role");


function format_price($price)
{
  return number_format($price) . "/=";
}
function format_date_time($date)
{
  return date('d M Y H:i:s', strtotime($date));
}

function format_date($date)
{
  return date('d M Y H:i', strtotime($date));
}

function format_date_only($date)
{
  return date('d M Y', strtotime($date));
}

function format_time_only($date)
{
  return date('H:i', strtotime($date));
}

?>



<!DOCTYPE html>
<html lang="en" class="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/datatable.css") ?>">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/select2.css") ?>">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css") ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <script src="<?php echo base_url("assets/js/jquery.js") ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/datepicker.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&display=swap');

    * {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', sans-serif !important;
    }

    .dataTables_wrapper {
      padding: 1.12rem 0;
      border-radius: 10px;
      overflow-y: auto;
    }

    .dataTables_wrapper table {
      padding: 4px 0;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background-color: white;
    }

    .tb-wrapper {
      overflow: auto;
      position: relative;
      background-color: #ddd;
      white-space: nowrap;
      padding: 8px;
      border-radius: 6px;
      max-height: 500px;
    }

    thead tr {
      border-radius: 6px;
    }

    tfoot th {
      padding: 6px 8px;
      text-align: left;
    }

    /* .fth, .ftd {
            position: sticky;
            left: 2px;
            background-color: #eee;
        } */

    table,
    thead th,
    tbody tr {
      border: 1px solid #ccc;
    }

    thead th {
      position: sticky;
      top: 2px;
      background-color: #ccc;
      text-align: left;
    }

    tbody tr:nth-child(even) {
      background-color: #eee;
    }

    tbody tr:hover {
      background-color: #ddd;
    }

    td,
    thead th {
      padding: 3px 10px;
    }

    tfoot td {
      display: flex;
      gap: 4px;
    }
  </style>
  <title>Masinde Store</title>
</head>

<body class="dark:bg-gray-900">

  <div class="antialiased bg-gray-50 dark:bg-gray-900">
    <nav
      class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
      <div class="flex flex-wrap justify-between items-center">
        <div class="flex justify-start items-center">
          <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
            aria-controls="drawer-navigation"
            class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"></path>
            </svg>
            <svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Toggle sidebar</span>
          </button>
          <a href="#" class="flex items-center justify-between mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="mr-2 w-8 h-8 text-rose-600 animate-pulse">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span class="self-center text-2xl font-semibold whitespace-nowrap hidden md:grid">
              <span class="text-gray-700 text-xl block">
                Hekima
              </span>
            </span>

          </a>

        </div>
        <div class="flex items-center lg:order-2">
          <button type="button" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation"
            class="p-2 mr-1 text-gray-500 rounded-lg md:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
            <span class="sr-only">Toggle search</span>
            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path clip-rule="evenodd" fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
              </path>
            </svg>
          </button>
          <button type="button"
            class="flex mx-3 text-sm bg-gray-100 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 "
            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
            <span class="sr-only">Open user menu</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-8 h-8 rounded-full">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

          </button>
          <!-- Dropdown menu -->
          <div class="hidden z-50 my-4 w-56 text-base list-none bg-white divide-y divide-gray-100 shadow rounded-xl"
            id="dropdown">
            <div class="py-3 px-4">
              <span class="block text-sm font-semibold text-gray-900">
                <?= $username ?>
              </span>
              <span class="block text-sm text-gray-900 truncate">
                <?= $role ?>
              </span>
            </div>

            <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
              <li>
                <a href="<?= site_url('login/logout') ?>"
                  class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign
                  out</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>