<?php
namespace App\Repositories\Item;

interface ItemRepository{
       /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param array $attr
     * @return mixed
     */
    public function save($attr = []);

    /**
     * @param $id
     * @param array $attr
     * @return mixed
     */
    public function update($id, $attr = []);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

     /**
     * @param $id
     * @param array $attr
     * @return mixed
     */
    public function refill($id, $attr = []);

}

?>