<?php

class Dashboard extends CI_Controller
{
  public function index()
  {
    $userId = $this->session->userdata("userId");

    if (empty($userId)) {
      return redirect("login");
    }

    //general stock
    $products = $this->db->get("product")->result();

    $general_stock = 0;
    $value = 0;
    foreach ($products as $product) {
      $general_stock += $product->inventory;
      $value += ($product->inventory * $product->buy_price);
    }

    //today expenses
    $today_expenses = $this->db->select("SUM(amount) as total_expenses_today")->where('DATE(createdAt)', date('Y-m-d'))->get('expense')->row();



    //monthly expenses
    $monthly_expenses = $this->db->select("SUM(amount) as total_expenses_monthly")->where('MONTH(createdAt)', date('m'))->get('expense')->row();

    //today income
    $income = $this->db->select("SUM(amountPaid) as cash_total")->from('orders')->where('DATE(created_at)', date('Y-m-d'))->get()->row();



    $monthly_income = $this->db->select("SUM(amountPaid) as cash_total")->from('orders')->where('MONTH(created_at)', date('m'))->get()->row();

    //today_profit
    $order_items = $this->db->select("p.buy_price as buyPrice, oi.price, oi.quantity")
      ->from('order_item oi')
      ->join('orders o', 'oi.order_id = o.id')
      ->join('product p', 'oi.product_id = p.id')
      ->where('DATE(o.created_at)', date('Y-m-d'))
      ->get()->result();
    $total_profit_today = 0;

    foreach ($order_items as $item) {
      $total_profit_today += (($item->price - $item->buyPrice) * $item->quantity);
    }


    //monthly_profit
    $order_items = $this->db->select("p.buy_price as buyPrice, oi.price, oi.quantity")
      ->from('order_item oi')
      ->join('orders o', 'oi.order_id = o.id')
      ->join('product p', 'oi.product_id = p.id')
      ->where('MONTH(o.created_at)', date('m'))
      ->get()->result();
    $total_profit_month = 0;

    foreach ($order_items as $item) {
      $total_profit_month += (($item->price - $item->buyPrice) * $item->quantity);
    }

    //best selling products
    $top_products = $this->db->select("p.name, SUM(oi.quantity) as quantity")
      ->from('order_item oi')
      ->join('product p', 'oi.product_id = p.id')
      ->group_by('oi.product_id')
      ->order_by('quantity', 'DESC')
      ->limit(7)
      ->get()->result();


    $balance = $this->db->get('sales')->row();


    // echo "<pre>";
    // print_r($sales);
    // echo "</pre>";
    // exit();

    $data = [
      "general_stock" => $general_stock,
      "stock_value" => $value,
      "expenses_today" => $today_expenses->total_expenses_today,
      "expenses_month" => $monthly_expenses->total_expenses_monthly,
      "total_cash_income" => $income->cash_total,
      "total_cash_income_monthly" => $monthly_income->cash_total,
      "profit_today" => $total_profit_today,
      "profit_month" => $total_profit_month,
      "top_products" => $top_products,

      "balance" => $balance->total,
    ];

    $this->load->view('dashboard', $data);
  }
}