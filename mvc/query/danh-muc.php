<?php
function load_all_category() { return pdo_query("SELECT * FROM category"); }
function load_one_category($id) { return pdo_query_one("SELECT * FROM category WHERE dm_id=?", $id); }
function insert_category($name) { pdo_execute("INSERT INTO category(dm_name) VALUES(?)", $name); }
function update_category($id, $name) { pdo_execute("UPDATE category SET dm_name=? WHERE dm_id=?", $name,$id); }
function delete_category($id) { pdo_execute("DELETE FROM category WHERE dm_id=?", $id); }
