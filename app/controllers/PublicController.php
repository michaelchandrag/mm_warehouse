<?php
namespace Controllers;

use Models\Outlet;
use Models\Moka;
use Models\Category;
use Models\Item;
use Models\SalesType;
use Models\Transaction;
use Models\ItemVariant;
use Models\Checkout;
use Models\PortalVisitor;

class PublicController {

	public function __construct() {
		
	}

	public function Hello($request, $response, $args) {
		$response->getBody()->write('Hello World');
		slackWebhook("Hey!");
    	return $response;
	}

	public function GetMokaBusinessProfile($request, $response, $args) {
		$moka = new Moka;
		$businesses = $moka->GetBusinessProfile();

		$data['businesses'] = $businesses;
		return throwJSON($response, $data);
	}

	public function SearchOutlets($request, $response, $args) {
		$filteredOutlets = Outlet::findOutlets();

		$data['outlets'] = $filteredOutlets;
		return throwJSON($response, $data);
	}

	public function SearchMokaOutlets($request, $response, $args) {
		$moka = new Moka;
		$data['outlets'] = $moka->GetListOutlets();
		return throwJSON($response, $data);
	}

	public function SyncMokaOutlets($request, $response, $args) {
		$moka = new Moka;
		$moka_outlets = $moka->GetListOutlets();
		$payload['new'] = [];
		$payload['update'] = [];
		foreach ($moka_outlets as $outlet) {
			$filter = array(
				"moka_outlet_id" => $outlet['id']
			);
			$data = array(
				"moka_outlet_id" => $outlet['id'],
				"name" => $outlet['name'],
				"address" => $outlet['address'],
				"phone_number" => $outlet['phone_number']
			);
			$exists_outlet = Outlet::findOutlets($filter);
			if (count($exists_outlet) <= 0) {
				$new_outlet = Outlet::createOutlet($data);
				$payload['new'][] = $data;
			} else {
				$update_outlet = Outlet::updateOutlet($filter, $data);
				$payload['update'][] = $data;
			}
		}
		slackWebhook("Sync Outlets on ".date('l, Y-m-d H:i:s')."\n");
		return throwJSON($response, $payload);
	}

	public function SyncMokaCategories($request, $response, $args) {
		$filteredOutlets = Outlet::findOutlets();
		$moka = new Moka;
		$payload = [];
		foreach ($filteredOutlets as $outlet) {
			$moka_categories = $moka->GetCategoriesByOutletID($outlet->moka_outlet_id);
			foreach ($moka_categories as $category) {
				$filter = array(
					"moka_category_id" => $category['id']
				);
				$data = array(
					"moka_category_id" => $category['id'],
					"moka_outlet_id" => $category['outlet_id'],
					"name" => $category['name'],
					"description" => $category['description']
				);
				$existsCategory = Category::findCategories($filter);
				if (count($existsCategory) <= 0) {
					$newCategory = Category::createCategory($data);
					$payload[$category['outlet_id']]['new'][] = $data;
				} else {
					$updatedCategory = Category::updateCategory($filter, $data);
					$payload[$category['outlet_id']]['update'][] = $data;
				}
			}
		}
		slackWebhook("Sync Categories on ".date('l, Y-m-d H:i:s')."\n");
		return throwJSON($response, $payload);
	}

	public function SyncMokaSalesType($request, $response, $args) {
		$filteredOutlets = Outlet::findOutlets();
		$moka = new Moka;
		$payload = [];
		foreach ($filteredOutlets as $outlet) {
			$moka_sales_type = $moka->GetSalesTypeByOutletID($outlet->moka_outlet_id);
			foreach ($moka_sales_type as $sales_type) {
				$filter = array(
					"moka_sales_type_id" => $sales_type['id']
				);
				$data = array(
					"moka_sales_type_id" => $sales_type['id'],
					"moka_outlet_id" => $sales_type['outlet_id'],
					"moka_business_id" => $sales_type['business_id'],
					"name" => $sales_type['name']
				);
				$existsSalesType = SalesType::findSalesType($filter);
				if (count($existsSalesType) <= 0) {
					$newSalesType = SalesType::createSalesType($data);
					$payload[$sales_type['outlet_id']]['new'][] = $data;
				} else {
					$updaedSalesType = SalesType::updateSalesType($filter, $data);
					$payload[$sales_type['outlet_id']]['update'][] = $data;
				}
			}
		}
		slackWebhook("Sync Sales Type on ".date('l, Y-m-d H:i:s')."\n");
		return throwJSON($response, $payload);
	}

	public function SyncMokaItems($request, $response, $args) {
		$filteredOutlets = Outlet::findOutlets();
		$moka = new Moka;
		$payload = [];
		foreach ($filteredOutlets as $outlet) {
			$moka_items = $moka->GetItemsByOutletID($outlet->moka_outlet_id);
			foreach ($moka_items as $item) {
				$filter = array(
					"moka_item_id" => $item['id'],
					"moka_outlet_id" => $item['outlet_id'],
					"moka_category_id" => $item['category_id']
				);
				$data = array(
					"moka_item_id" => $item['id'],
					"moka_outlet_id" => $item['outlet_id'],
					"moka_category_id" => $item['category_id'],
					"name" => $item['name'],
					"description" => $item['description']
				);

				$existsItem = Item::findItems($filter);
				if (count($existsItem) <= 0) {
					$newItem = Item::createItem($data);
					$payload[$item['category_id']]['new'][] = $data;
				} else {
					$updatedItem = Item::updateItem($filter, $data);
					$payload[$item['category_id']]['update'][] = $data;
				}

				$item_variants = $item['item_variants'];
				foreach ($item_variants as $variant) {
					$filter = array(
						"moka_item_id" =>  $variant['item_id'],
						"moka_item_variant_id" => $variant['id']
					);
					$data = array(
						"moka_item_id" => $variant['item_id'],
						"moka_item_variant_id" => $variant['id'],
						"name" => $variant['name'],
						"price" => $variant['price']
					);

					$existsVariant = ItemVariant::findItemVariants($filter);
					if (count($existsVariant) <= 0) {
						$newVariant = ItemVariant::createItemVariant($data);
						$payload[$item['category_id']]['variant']['new'][] = $data;
					} else {
						$updatedVariant = ItemVariant::updateItemVariant($filter, $data);
						$payload[$item['category_id']]['variant']['update'][] = $data;
					}
				}
			}
		}
		slackWebhook("Sync Items on ".date('l, Y-m-d H:i:s')."\n");
		return throwJSON($response, $payload);
	}

	public function SyncMokaLatestTransactions($request, $response, $args) {
		$body = $request->getParsedBody();
		$filteredOutlets = Outlet::findOutlets();

		$moka = new Moka;
		$payload = [];
		$filteredItems = [];
		foreach ($filteredOutlets as $outlet) {
			$moka_transactions = $moka->GetLatestTransactionsByOutletID($outlet->moka_outlet_id, $body);
			foreach ($moka_transactions as $transaction) {
				$filter = array(
					"moka_transaction_id" => $transaction['id'],
					"moka_payment_no" => $transaction['payment_no']
				);
				$data = array(
					"moka_transaction_id" => $transaction['id'],
					"moka_payment_no" => $transaction['payment_no'],
					"moka_created_at" => TZtoDT($transaction['created_at']),
					"moka_updated_at" => TZtoDT($transaction['updated_at']),
					"moka_parent_payment_created_at" => TZtoDT($transaction['parent_payment_created_at']),
					"moka_total_collected" => $transaction['total_collected'],
					"moka_total_item_price_amount" => $transaction['total_item_price_amount'],
					"moka_name" => $transaction['name'],
					"moka_parent_payment_id" => $transaction['parent_payment_id'],
					"moka_payment_type" => $transaction['payment_type'],
					"moka_payment_type_label" => $transaction['payment_type_label'],
					"moka_customer_id" => $transaction['customer_id'],
					"moka_payment_note" => $transaction['payment_note'],
					"moka_discounts" => $transaction['discounts'],
					"moka_subtotal" => $transaction['subtotal'],
					"moka_gratuities" => $transaction['gratuities'],
					"moka_taxes" => $transaction['taxes'],
					"moka_tendered" => $transaction['tendered'],
					"moka_change" => $transaction['change'],
					"moka_transaction_date" => $transaction['transaction_date'],
					"moka_transaction_time" => $transaction['transaction_time'],
					"moka_collected_by" => $transaction['collected_by'],
					"moka_served_by" => $transaction['served_by'],
					"moka_outlet_id" => $transaction['outlet_id'],
					"moka_guid" => $transaction['guid'],
					"moka_customer_name" => $transaction['customer_name'],
					"moka_customer_phone" => $transaction['customer_phone'],
					"moka_customer_email" => $transaction['customer_email']
				);

				$existsTransactions = Transaction::findTransactions($filter);
				if (count($existsTransactions) <= 0) {
					$newTransaction = Transaction::createTransaction($data);
					$payload[$outlet->moka_outlet_id]['new'][] = $data; 
				} else {
					$updatedTransaction = Transaction::updateTransaction($filter, $data);
					$payload[$outlet->moka_outlet_id]['update'][] = $data;
				}

				// start of checkouts
				
				$moka_checkouts = $transaction['checkouts'];
				foreach ($moka_checkouts as $checkout) {
					$filter = array(
						"moka_checkout_id" => $checkout['id']
					);
					$data = array(
						"moka_transaction_id" => $transaction['id'],
						"moka_checkout_id" => $checkout['id'],
						"moka_custom_amount" => $checkout['custom_amount'],
						"moka_item_variant_id" => $checkout['item_variant_id'],
						"moka_quantity" => $checkout['quantity'],
						"moka_discount_amount" => $checkout['discount_amount'],
						"moka_tax_amount" => $checkout['tax_amount'],
						"moka_business_id" => $checkout['business_id'],
						"moka_payment_id" => $checkout['payment_id'],
						"moka_item_id" => $checkout['item_id'],
						"moka_item_discount" => $checkout['item_discount'],
						"moka_tax_amount" => $checkout['tax_amount'],
						"moka_business_id" => $checkout['business_id'],
						"moka_payment_id" => $checkout['payment_id'],
						"moka_item_id" => $checkout['item_id'],
						"moka_item_discount" => $checkout['item_discount'],
						"moka_item_price_library" => $checkout['item_price_library'],
						"moka_item_price" => $checkout['item_price'],
						"moka_item_price_discount" => $checkout['item_price_discount'],
						"moka_gratuity_amount" => $checkout['gratuity_amount'],
						"moka_item_price_discount_gratuity" => $checkout['item_price_discount_gratuity'],
						"moka_total_price" => $checkout['total_price'],
						"moka_item_price_quantity" => $checkout['item_price_quantity'],
						"moka_category_name" => $checkout['category_name'],
						"moka_category_id" => $checkout['category_id'],
						"moka_item_name" => $checkout['item_name'],
						"moka_item_variant_name" => $checkout['item_variant_name'],
						"moka_gross_sales" => $checkout['gross_sales'],
						"moka_outlet_id" => $checkout['outlet_id'],
						"moka_net_sales" => $checkout['net_sales'],
						"moka_sales_type_id" => $checkout['sales_type_id'],
						"moka_sales_type_name" => $checkout['sales_type_name'],
						"moka_price" => $checkout['price'] 
					);

					$existsCheckout = Checkout::findCheckouts($filter);
					if (count($existsCheckout) <= 0) {
						$newCheckout = Checkout::createCheckout($data);
					} else {
						$updatedCheckout = Checkout::updateCheckout($filter, $data);
					}

					// filter item name here
					$explodeName = explode(" + ", $checkout['item_variant_name']);
					foreach ($explodeName as $name) {
						$filteredItems[$name.'['.$checkout['sales_type_name'].']'] = (isset($filteredItems[$name.'['.$checkout['sales_type_name'].']']) ? $filteredItems[$name.'['.$checkout['sales_type_name'].']'] : 0) + $checkout['quantity'];
					}
				}

				// end of checkouts

			}
			$payload['total']['new'] = isset($payload['total']['new']) ? $payload['total']['new'] : 0 + isset($payload[$outlet->moka_outlet_id]['new']) ? count($payload[$outlet->moka_outlet_id]['new']) : 0;
			$payload['total']['update'] = isset($payload['total']['update']) ? $payload['total']['update'] : 0 + isset($payload[$outlet->moka_outlet_id]['update']) ? count($payload[$outlet->moka_outlet_id]['update']) : 0;
		}

		$msg = "Sync Transaction on ".date("l, Y-m-d H:i:s")."\nSince: ".((!empty($body['since'])) ? $body['since'] : '')."\nFrom: ".((!empty($body['until'])) ? $body['until'] : '')."\nResult => New: ".(isset($payload['total']['new']) ? $payload['total']['new'] : 0)." | Update: ".(isset($payload['total']['update']) ? $payload['total']['update'] : 0)."\n";
		foreach ($filteredItems as $key => $value) {
			$msg .= "\n".$key." : ".$value;
		}
		slackWebhook($msg);
		return throwJSON($response, $payload);
	}

	public function PortalVisitor($request, $response, $args) {
		$body = $request->getParsedBody();
		$payload = [];
		
		$data = array(
			"source" => $body['source'],
			"destination" => $body['destination']
		);
		$newPortalVisitor = PortalVisitor::createPortalVisitor($data);
		$payload['success'] = true;
		$payload['message'] = "Success create portal visitor.";
		$msg = "Somebody visited at ".$data['destination'];
		// slackWebhook($msg);
		return throwJSON($response, $payload);
	}

}