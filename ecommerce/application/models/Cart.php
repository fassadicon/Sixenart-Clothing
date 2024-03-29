<?php
class Cart extends CI_Model
{
    function get_all()
    {
        $query = "SELECT * FROM products";
        return $this->db->query($query)->result_array();
    }

    function get_by_user_id($user_id)
    {
        $query = "SELECT * FROM cart_items 
        JOIN products ON cart_items.product_id = products.id
        WHERE cart_items.user_id = ?";
        $values = array(
            $user_id
        );
        return $this->db->query($query, $values)->result_array();
    }

    function get_total_amount_by_user_id($user_id)
    {
        $query = "SELECT SUM(cart_items.quantity * products.price) as totalAmount FROM cart_items 
        JOIN products ON cart_items.product_id = products.id
        WHERE cart_items.user_id = ?";
        $values = array(
            $user_id
        );
        return $this->db->query($query, $values)->row_array();
    }
    // public function fetch_limit($result, $per_page){
    //     return $this->db->query("SELECT * FROM products LIMIT ?,?", array($result, $per_page))->result_array();
    // }

    // function get_by_name($product)
    // {
    //     if (strlen($product['name'] == 0)) {
    //         $product['name'] = '%';
    //     } else {
    //         $product['name'] = '%' . $this->security->xss_clean($product['name']) . '%';
    //     }
    //     $query = "SELECT * FROM products WHERE name LIKE ?";
    //     $values = array(
    //         $product['name']
    //     );
    //     return $this->db->query($query, $values)->result_array();
    // }

    function store($post)
    {
        $query = "INSERT INTO cart_items(user_id, product_id, quantity, created_at) VALUES (?,?,?,?)";
        $values = array(
            $this->security->xss_clean($this->session->userdata('user_id')),
            $this->security->xss_clean($post['product_id']),
            $this->security->xss_clean($post['quantity']),
            $this->security->xss_clean(date('Y-m-d H:i:s'))
        );
        return $this->db->query($query, $values);
    }

    function remove($item_id, $user_id) {
        $query = "DELETE from cart_items WHERE product_id = ? AND user_id = ?";
        $values = array($item_id, $user_id);
        $this->db->query($query, $values);
    }

    function remove_in_cart($cart_id) {
        $query = "DELETE from cart_items WHERE id = ?";
        $values = array($cart_id);
        $this->db->query($query, $values);
    }

}
