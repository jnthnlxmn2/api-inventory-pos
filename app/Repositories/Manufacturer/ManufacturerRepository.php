<?php
namespace App\Repositories\Manufacturer;

interface ManufacturerRepository{
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
}

?>