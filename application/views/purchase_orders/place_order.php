  <?php include APPPATH . "/views/includes/header.php"?>
  <?php include APPPATH . "/views/includes/sidebar.php"?>

  <main class="py-4 px-2 lg:px-4 md:ml-64 h-auto pt-20 ">
    <?php if($this->session->flashdata('purchaseorderitem_canceled')): ?>
              <div id="toast-success" class="flex items-center w-full p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ml-3 text-sm font-normal"><?= $this->session->flashdata('purchaseorderitem_canceled') ?></div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
    <?php elseif($this->session->flashdata('complete_purchaseorder_success')): ?>
              <div id="toast-success" class="flex items-center w-full p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ml-3 text-sm font-normal"><?= $this->session->flashdata('complete_purchaseorder_success') ?></div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        <?php else: ?>
            <div class="flex p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Kabla hujaweka oda hakikisha: </span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        <li>Umefanya maboresho ya bei za bidhaa unazotaka kuweka oda ziendane na bei ya sasa.</li>
                        <li>Kama bidhaa unayotaka kuweka oda haipo kwenye mfumo, nenda uakisajili kwanza.</li>
                        <li>Kama vyote hivi viko sawa endelea kuweka oda 😎</li>
                    </ul>
                </div>
            </div>
        <?php endif ?>


            <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-start">
            <div class="max-w-screen-xl mx-auto lg:px-12 w-full">
                <!-- Start coding here -->
                <?php if(count($orderitems) == 0): ?>
                <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                    <?php echo form_open('purchaseOrder/create', array('class'=> 'flex flex-col gap-4 w-full p-4')); ?>
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-center gap-4 shrink">
                        <div>
                            <label for="supplier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose Supplier</label>
                            <select id="supplier" name="supplierId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500">
                                <?php foreach($suppliers as $supplier): ?>
                                    <option value="<?php echo $supplier->id ?>"><?php echo $supplier->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose branch</label>
                            <select id="branch" name="branchId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500">
                                <option value="0">--select--</option>
                                <option value="1">MAIN STORE</option>
                                    <option value="2">UYOLE SHOP</option>
                                    <option value="3">MBALIZI STORE</option>
                            </select>
                        </div>
                        <div class="relative w-full">
                            <label for="supplier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Products</label>
                            <select name="order_items[]" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500" style="width: 100%" id="select2" multiple="true">
                                <?php foreach($products as $product): ?>
                                    <option value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="flex items-center inset-0 m-auto w-fit h-fit justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-sky-300 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add to order
                    </button>
                <?php echo form_close() ?>
            </div>
            <?php endif ?>



            <section class="bg-gray-50 dark:bg-gray-900 py-3">
                <div class="mx-auto max-w-screen-xl lg:px-4">
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <form class="flex items-center">
                                    <label for="simple-search" class="sr-only">Search</label>
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" readonly  id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500" placeholder="Search" required="">
                                    </div>
                                </form>
                            </div>
                            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button id="updateButton" type="button" class="flex items-center justify-center text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-sky-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Calculate Price
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="productTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">PRODUCT NAME</th>
                                        <th scope="col" class="px-4 py-3">BRAND</th>
                                        <th scope="col" class="px-4 py-3">SUPPLIER</th>
                                        <th scope="col" class="px-4 py-3">BUYING PRICE</th>
                                        <th scope="col" class="px-4 py-3">QUANTITY</th>
                                        <th scope="col" class="px-4 py-3">PRICE</th>
                                        <th scope="col" class="px-4 py-3">
                                            CANCEL
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalPrice = 0; ?>
                                    <?php $orderId = null; ?>
                                    <?php foreach($orderitems as $orderitem): ?>
                                    <tr class="border-b dark:border-gray-700">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?= $orderitem->product_name ?></th>
                                        <td class="px-4 py-3">brand</td>
                                        <td class="px-4 py-3"><?= $orderitem->supplier_name ?></td>
                                        <td class="px-4 py-3"><?= $orderitem->buyPrice ?></td>
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="order_item_id[]" value="<?= $orderitem->id ?>">
                                            <input type="number" name="quantity[]" value="<?= $orderitem->quantity ?>" id="quantity" class="bg-gray-50 border max-w-[100px] min-w-[80px] border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                                        </td>
                                        <?php $orderId = $orderitem->purchaseorderId ?>
                                        <?php $totalPrice += ($orderitem->buyPrice * $orderitem->quantity);?>
                                        <td class="px-4 py-3"><?= format_price($orderitem->buyPrice * $orderitem->quantity) ?></td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <a href="<?= site_url('purchaseOrder/cancel_order/'.$orderitem->id) ?>" type="button" class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="sr-only">cancel order</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                               
                            </span>

                            <?php echo form_open("purchaseOrder/complete_order")?>
                            <input type="hidden" name="purchaseorderId" value="<?= $orderId ?>">
                            <input type="hidden" name="status" value="complete">
                            <ul class="flex flex-col gap-3">
                                <li class="flex gap-3">
                                    <span class="text-xl text-slate-400"> Tota Price: </span>  
                                    <input type="hidden" name="total" value="<?= $totalPrice ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                    <span class="text-green-400 text-xl font-bold">
                                        <?= format_price($totalPrice) ?>
                                    </span> 
                                </li>
                                <li class="flex gap-3">
                                    <span class="text-xl text-slate-100"> Pay </span>  
                                    <input type="number" name="paid" value="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                </li>
                                <li class="w-full flex justify-end">
                                    <button id="completeOrderBtn" type="submit" class="flex items-center justify-center text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-sky-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Complete Order
                                </button>
                                </li>
                            </ul>
                            <?php echo form_close()?>
                        </nav>
                    </div>
                </div>
                </section>
        </section>
  </main>
</div>


<script>
    $(document).ready(function() {
   $('#updateButton').on('click', function() {
      var formData = $('#productTable :input').serializeArray();
      console.log('Data to be sent:', formData);
      $.ajax({
         url: 'update_order/',
         type: 'POST',
         data: formData,
         success: function(response) {
            location.reload();
            console.log('Data updated successfully:', response);
         },
         error: function(error) {
            console.error('Error updating data:', error);
         }
      });
   });
});

</script>

<?php include APPPATH . "/views/includes/footer.php"?>