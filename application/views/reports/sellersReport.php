  <?php include APPPATH . "/views/includes/header.php"?>
  <?php include APPPATH . "/views/includes/sidebar.php"?>

  <main class="p-4 md:ml-64 h-auto pt-20 ">
    <section class="bg-gray-50 dark:bg-gray-900">
        <h2 class="text-xl font-semibold text-slate-700 my-3">Sellers Reports</h2>
        <p class="text-slate-400 my-2">Select seller below to see their sales details</p>
       <div class="tb-wrapper">
       <table>
        <thead>
            <tr>
                <th class="fth" style="font-weight: 400; font-size: small">S/N</th>
                <th style="font-weight: 400; font-size: small">USERNAME</th>
                <th style="font-weight: 400; font-size: small">POSITION</th>
                <th style="font-weight: 400; font-size: small">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php $rowId = 1?>
            <?php foreach($sellers as $seller): ?>
                <tr style="cursor: pointer">
                    <td class="ftd"><?= $rowId ? '0'.$rowId++ : $rowId++ ?></td>
                    <td>
                        <a href="<?= site_url('sellersReport/'.$seller->id)?>"><?= $seller->username ?></a>
                    </td>
                    <td><?= $seller->role ?></td>
                    <td>
                        <a href="<?= site_url('sellersReport/sellerReportDetail/'.$seller->id)?>" class="px-3 py-2 text-white bg-violet-500 rounded-2xl border-none outline-none hover:bg-violet-500/50 focus:outline-1 focus:outline-violet-700">view sales</a>
                    </td>
                </tr>
                </tr>
            <?php endforeach?>
        </tbody>
        <tfoot>
            <td>
                <!-- <?php //echo $this->pagination->create_links(); ?> -->
            </td>
        </tfoot>
       </table>
   </div>
   
    </section>
  </main>
  </div>

  <?php include APPPATH . "/views/includes/footer.php"?>