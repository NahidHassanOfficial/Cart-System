<?php
class Product
{
    private $db;
    private $table = 'products';
    private $variantsTable = 'product_variants';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($productData)
    {
        try {
            $this->db->beginTransaction();

            // insert product item
            $sqlQuery = $this->db->prepare("INSERT INTO {$this->table} (name, category) VALUES (?, ?)");
            $sqlQuery->execute([$productData['name'], $productData['category']]);

            //get the new inserted record id
            $productId = $this->db->lastInsertId();

            // insert variations with foreign key constrained
            $sqlQuery = $this->db->prepare(
                "INSERT INTO {$this->variantsTable} (product_id, variant_name, price, image_path)
                 VALUES (?,?,?,?)"
            );
            foreach ($productData['variants'] as $variant) {
                $sqlQuery->execute([$productId, $variant['name'], $variant['price'], $variant['image']]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAll()
    {
        try {
            //make sql query to fetch data with joining
            $sqlQuery = "SELECT products.*,
            product_variants.id AS variant_id,
            product_variants.variant_name,
            product_variants.price AS variant_price,
            product_variants.image_path AS variant_image
            FROM {$this->table} LEFT JOIN {$this->variantsTable} ON {$this->table}.id = {$this->variantsTable}.product_id";

            //prepare and execute query
            $statement = $this->db->prepare($sqlQuery);
            $statement->execute();

            //return with fetched data with associative array format
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return "Something wrong!";
        }
    }
}
