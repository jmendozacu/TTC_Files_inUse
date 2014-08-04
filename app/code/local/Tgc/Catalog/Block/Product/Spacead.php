<?php
/**
 * Only reason this class exists is because there are two blocks on PDP that need to be holepunched that use same block.
 * Each holepunched block must have a different class! If classes are same magento has no way to distinguish between two blocks, and there is a collision.
 *
 */

class Tgc_Catalog_Block_Product_Spacead extends Mage_Catalog_Block_Product
{


}
