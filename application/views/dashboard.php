<?php include APPPATH . "/views/includes/header.php" ?>
<?php include APPPATH . "/views/includes/sidebar.php" ?>

<main class="py-4 px-2 lg:px-4 md:ml-64 h-auto pt-20 grid gap-5">
  
     <h3 class="text-gray-600 text-xl font-semibold">Admin Dashboard</h3>
  <section class="grid grid-cols-2 gap-4">
     <div class="w-full flex items-center border border-slate-200 rounded shadow">
      <div class="p-4">
        <p class="text-slate-500">General Stock</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= number_format($general_stock )?> <small class="text-xs text-slate-400">products</small>
          </p>
          <p class="flex gap-2">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">VALUE</span>
            <span class="text-green-700 text-xl">
              <?= format_price($stock_value) ?>
            </span>
          </p>
        </div>
      </div>
    </div>

    
    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="p-4">
        <p class="text-slate-500">Balance</p>
        <div>
          <p class="text-gray-700 text-xl font-semibold">
            <?= number_format($balance) ?>
          </p>
        </div>
      </div>
    </div>

  </section>



  <h3 class="text-gray-600 text-xl font-semibold">Daily sales</h3>
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">


    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="bg-gradient-to-r from-rose-900 to-rose-950 h-full flex items-center justify-center">
        <svg class="text-slate-300 w-14 h-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="lucide lucide-align-horizontal-distribute-center">
          <rect width="6" height="14" x="4" y="5" rx="2" />
          <rect width="6" height="10" x="14" y="7" rx="2" />
          <path d="M17 22v-5" />
          <path d="M17 7V2" />
          <path d="M7 22v-3" />
          <path d="M7 5V2" />
        </svg>
      </div>
      <div class="p-4">
        <p class="text-slate-500">Expenses</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($expenses_today) ?>
          </p>
          <p class="flex gap-2 items-center">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">
              <!-- avoid division by zero -->
              <?php if ($total_cash_income > 0): ?>
                <span class="text-red-500">
                  <?= round(($expenses_today / $total_cash_income) * 100, 2) . "%" ?>
                </span> of your income
              </span>
            <?php endif ?>
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="bg-gradient-to-r from-zinc-900 to-zinc-950 h-full flex items-center justify-center">
        <svg class="text-slate-300 w-14 h-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="lucide lucide-align-horizontal-distribute-center">
          <rect width="6" height="14" x="4" y="5" rx="2" />
          <rect width="6" height="10" x="14" y="7" rx="2" />
          <path d="M17 22v-5" />
          <path d="M17 7V2" />
          <path d="M7 22v-3" />
          <path d="M7 5V2" />
        </svg>
      </div>
      <div class="p-4">
        <p class="text-slate-500">Income</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($total_cash_income) ?>
          </p>
          <p class="flex gap-2">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">NET</span>
            <span class="text-green-700 text-xl">
              <?= format_price($total_cash_income - $expenses_today) ?>
            </span>
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="bg-gradient-to-r from-emerald-900 to-emerald-950 h-full flex items-center justify-center">
        <svg class="text-slate-300 w-14 h-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="lucide lucide-align-horizontal-distribute-center">
          <rect width="6" height="14" x="4" y="5" rx="2" />
          <rect width="6" height="10" x="14" y="7" rx="2" />
          <path d="M17 22v-5" />
          <path d="M17 7V2" />
          <path d="M7 22v-3" />
          <path d="M7 5V2" />
        </svg>
      </div>
      <div class="p-4">
        <p class="text-slate-500">Profit</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($profit_today) ?>
          </p>
          <p class="flex gap-2">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">NET</span>
            <span class="text-green-700 text-xl">
              <?= format_price($profit_today - $expenses_today) ?>
            </span>
          </p>
        </div>
      </div>
    </div>
  </section>



  <h3 class="text-gray-600 text-xl font-semibold">Monthly sales</h3>
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!--  -->

    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="p-4">
        <p class="text-slate-500">Expenses</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($expenses_month) ?>
          </p>
          <p class="flex gap-2 items-center">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">
              <!-- avoid division by zero -->
              <?php if ($total_cash_income_monthly > 0): ?>
                <span class="text-red-500">
                  <?= round(($expenses_month / $total_cash_income_monthly) * 100, 2) . "%" ?>
                </span> of your income
              </span>
            <?php endif ?>
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="p-4">
        <p class="text-slate-500">Income</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($total_cash_income_monthly) ?>
          </p>
          <p class="flex gap-2">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">NET</span>
            <span class="text-green-700 text-xl">
              <?= format_price($total_cash_income_monthly - $expenses_month) ?>
            </span>
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center border border-slate-200 rounded shadow">
      <div class="p-4">
        <p class="text-slate-500">Profit</p>
        <div>
          <p class="text-orange-700 text-xl font-semibold">
            <?= format_price($profit_month) ?>
          </p>
          <p class="flex gap-2">
            <span class="bg-blue-900 text-white text-sm inset-0 m-auto px-1 rounded">NET</span>
            <span class="text-green-700 text-xl">
              <?= format_price($profit_month - $expenses_month) ?>
            </span>
          </p>
        </div>
      </div>
    </div>
  </section>


</main>
</div>


<?php include APPPATH . "/views/includes/footer.php" ?>