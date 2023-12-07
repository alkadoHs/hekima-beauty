<?php include APPPATH . "/views/includes/header.php" ?>
<?php include APPPATH . "/views/includes/sidebar.php" ?>

<?php

$totalProducts = 0;
$totalRevenue = 0;
$totalProfit = 0;
$totalExpenses = 0;

foreach ($expenses as $expense) {
    $totalExpenses += $expense["amount"];
}

foreach ($orders as $order) {
    $totalRevenue += $order["paid"];

    foreach ($order["orderItems"] as $orderitem) {
        $totalProfit += $orderitem["profit"];
        $totalProducts += $orderitem["quantity"];
    }
}



?>


<main class="p-4  md:ml-64 h-auto pt-20 ">
    <div class="grid mb-3 md:flex md:justify-between md:items-center md:mb-0">
        <div>
            <h2 class="text-xl font-semibold text-slate-700 my-3 flex gap-2">
                <a href="#">
                    <svg class="text-sky-950" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-arrow-left-square">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <path d="m12 8-4 4 4 4" />
                        <path d="M16 12H8" />
                    </svg>
                </a>
                <span> Hello <b class="text-sky-700">
                        <?= $user->username ?>
                    </b> </span>
            </h2>
            <?php if ($date == date('d-m-Y')): ?>
                <p class="text-slate-400 my-2">Date: <span class="text-orange-500">
                        <?= $date ?>
                    </span> </p>
            <?php else: ?>
                <p class="text-slate-400 my-2">Date: <span class="text-orange-500">
                        <?= $date ?>
                    </span> <a class="text-sky-600 font-semibold p-1 border border-blue-600 rounded-lg"
                        title="View today's sales" href="<?= site_url('mysales') ?>">Today</a> </p>
            <?php endif ?>
        </div>
        <form action="<?php echo base_url('mysales/filter_seller_dashboard') ?>" date-rangepicker
            datepicker-format="y-mm-dd" class="flex items-center" method="POST">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input name="start" type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500"
                    placeholder="Select date start" require>
            </div>
            <span class="mx-4 text-gray-500">to</span>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input name="end" type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500"
                    placeholder="Select date end" require>
            </div>
            <input type="hidden" name="userId" value="<?= $user->id ?>">
            <div class="relative ml-2">
                <button type="submit"
                    class="text-sky-700 border border-sky-700 hover:bg-sky-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-sky-500 dark:text-sky-500 dark:hover:text-white dark:focus:ring-sky-800 dark:hover:bg-sky-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-filter">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    <span class="sr-only">Icon description</span>
                </button>
            </div>
        </form>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="rounded-lg shadow-lg p-4 bg-gradient-to-r from-green-100 to-pink-100  dark:border-gray-600 h-36">
            <p class="text-slate-400 text-xl">Sold Products</p>
            <p class="text-3xl font-semibold text-slate-700">
                <?= number_format($totalProducts) ?>
            </p>
        </div>

        <div class="rounded-lg shadow-lg p-4 bg-pink-200 dark:border-gray-600 h-36">
            <p class="text-slate-400 text-xl">Expenses </p>
            <p class="text-xl text-orange-500"><a class="hover:underline" href="#expenses">
                    <?= format_price($totalExpenses) ?>
                </a> </p>
        </div>

        <div class="rounded-lg shadow-lg bg-gradient-to-r from-green-100 to-pink-100 p-4  dark:border-gray-600 h-36">
            <p class="text-slate-400 text-xl">Income </p>
            <p class="text-3xl font-semibold text-slate-700">
                <?= format_price($totalRevenue) ?>
            </p>
        </div>


        <div class="rounded-lg shadow-lg bg-gradient-to-r from-cyan-100 to-sky-100 p-4  dark:border-gray-600 h-36">
            <div>
                <p class="text-slate-400"> Net Income</p>
                <p class="text-3xl font-semibold text-green-500">
                    <?= format_price($totalRevenue - $totalExpenses) ?>
                </p>
            </div>
        </div>
    </div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <h2 class="text-xl  text-slate-700 my-3">-Products Sold By <b class="text-sky-700">
                <?= $user->username ?>
            </b> </h2>
        <?php if (count($orders) > 0): ?>
            <div id="accordion-arrow-icon" data-accordion="open">
                <?php foreach ($orders as $order): ?>
                    <h2 id="<?= $order['id'] ?>">
                        <button type="button"
                            class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                            data-accordion-target="#accordion-arrow-icon<?= $order['id'] ?>" aria-expanded="true"
                            aria-controls="accordion-arrow-icon<?= $order['id'] ?>">
                            <span>Customer ID: <b class="text-orange-500">
                                    <?= $order['id'] ?>
                                </b> </span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5 5 1 1 5" />
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-arrow-icon<?= $order['id'] ?>" class="hidden" aria-labelledby="<?= $order['id'] ?>">
                        <div class="p-5 border overflow-auto max-h-80 border-t-0 border-gray-200 dark:border-gray-700">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="font-weight: 400; font-size: small">PRODUCT</th>
                                        <th style="font-weight: 400; font-size: small">QUANTITY</th>
                                        <th style="font-weight: 400; font-size: small">PRICE</th>
                                        <th style="font-weight: 400; font-size: small">TOTAL PRICE</th>
                                        <!-- <th style="font-weight: 400; font-size: small">PROFIT</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalItemsPrice = 0 ?>
                                    <?php $totalItemsProfit = 0 ?>
                                    <?php foreach ($order['orderItems'] as $order_detail): ?>
                                        <tr>
                                            <td>
                                                <?= $order_detail['product'] ?>
                                            </td>
                                            <td>
                                                <?= $order_detail['quantity'] ?>
                                            </td>
                                            <?php $totalItemsPrice += $order_detail['net_price'] ?>
                                            <?php $totalItemsProfit += $order_detail['profit'] ?>
                                            <td>
                                                <?= format_price($order_detail['price']) ?>
                                            </td>
                                            <td>
                                                <?= format_price($order_detail['net_price']) ?>
                                            </td>
                                            <!-- <td><? //= format_price($order_detail['profit']) ?></td> -->
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot class="text-green-600">
                                    <tr>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <?= format_price($totalItemsPrice) ?>
                                        </th>
                                        <!-- <th><? //= format_price($totalItemsProfit)  ?></th> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endforeach ?>

                <div>
                    <h2 class="text-gray-500 text-xl my-4" id="expenses">- Expenses</h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="font-weight: 400; font-size: small; background:rgb(255 90 31)">S/N</th>
                                <th style="font-weight: 400; font-size: small; background:rgb(255 90 31)">DESCRIPTION</th>
                                <th style="font-weight: 400; font-size: small; background:rgb(255 90 31)">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rowId = 1;
                            $total_amount = 0;
                            ?>
                            <?php foreach ($expenses as $expense): ?>
                                <tr>
                                    <td>
                                        <?= $rowId < 10 ? '0' . $rowId++ : $rowId++ ?>
                                    </td>
                                    <td>
                                        <?= $expense['description'] ?>
                                    </td>
                                    <?php $total_amount += $expense['amount'] ?>
                                    <td>
                                        <?= format_price($expense['amount']) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot class="text-green-600">
                            <th>#</th>
                            <th>TOTAL</th>
                            <th>
                                <?= format_price($total_amount) ?>
                            </th>
                        </tfoot>
                    </table>
                </div>

            </div>
        <?php else: ?>
            <img src="<?php echo base_url('assets/images/no-products.svg') ?>"
                class="max-w-md inset-0 m-auto aspect-square animate-pulse" alt="no-products">
            <p class="text-xl text-sky-700 animate-bounce text-center">No products sold 🙂</p>
        <?php endif ?>
    </section>
</main>
</div>

<?php include APPPATH . "/views/includes/footer.php" ?>