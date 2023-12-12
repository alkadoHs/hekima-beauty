<?php include APPPATH . "/views/includes/header.php" ?>
<?php include APPPATH . "/views/includes/sidebar.php" ?>

<main class="py-4 px-2 lg:px-4 md:ml-64 h-auto pt-20 ">
    <section class="bg-gray-50 dark:bg-gray-900">
        <!-- ALERTS -->
        <div class="mx-auto max-w-screen-xl lg:px-6">
            <?php if ($this->session->flashdata('new_stock_added')): ?>
                <div id="toast-success"
                    class="flex items-center w-full p-4 mb-4 text-green-800 bg-green-100 rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                    role="alert">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                        </svg>
                        <span class="sr-only">Check icon</span>
                    </div>
                    <div class="ml-3 text-sm font-normal">
                        <?= $this->session->flashdata('new_stock_added') ?>
                    </div>
                    <button type="button"
                        class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                        data-dismiss-target="#toast-success" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
            <div class="flex flex-col justify-center m-5">
                 <h1 class="text-2xl font-semibold text-gray-700">Stock Management</h1>
                 <p class="text-gray-400">Track Initial stock(I.STOCK), Available stock(A.STOCK), New stock(N.STOCK) add stock.</p>
            </div>


            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th style="font-weight: 400; font-size: small">S/N</th>
                        <th style="font-weight: 400; font-size: small">PRODUCT</th>
                        <th style="font-weight: 400; font-size: small">I.STOCK</th>
                        <th style="font-weight: 400; font-size: small">A.STOCK</th>
                        <th style="font-weight: 400; font-size: small">N.STOCK</th>
                        <th style="font-weight: 400; font-size: small">LAST UPDATE</th>
                        <th style="font-weight: 400; font-size: small">ADD STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowId = 1 ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?= $rowId < 10 ? "0" . $rowId++ : $rowId++ ?>
                            </td>
                            <td>
                                <?= $product->name ?>
                            </td>
                            <td class="text-yellow-600">
                                <?= number_format($product->quantity) ?>
                            </td>
                            <td class="text-blue-500">
                                <?= number_format($product->inventory) ?>
                            </td>
                            <td class="text-green-500">
                                + <?= number_format($product->new_stock) ?>
                            </td>
                            <td class="text-violet-500">
                                 <?=  format_date_only($product->updated_at) ?>
                            </td>
                            <td>
                                <?php echo form_open("product/add_new_stock")?>
                                <input type="hidden" name="id" value="<?= $product->id ?>">
                                <div class="flex gap-2">
                                    <input type="number" class="py-1 px-2 border border-slate-400 max-w-[80px] rounded" name="new_stock" required>
                                    <button type="submit"
                                            class="py-1 px-2 w-fit flex items-center text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            ADD
                                        </button>
                                    </div>
                                <?php echo form_close()?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <!-- <tfoot>
            <tr>
                <th>AMOUNT: </th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot> -->
            </table>
    </section>
</main>
</div>

<?php include APPPATH . "/views/includes/footer.php" ?>