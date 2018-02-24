<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 17:32
 */

namespace App\Controllers;


use think\Db;

class AddressController extends Controller
{

    public function getList()
    {

        $addressList = Db::name('address')->where()->select()->toArray();
        const addressList = yield _this.model('address').where({ user_id: think.userId }).select();
      let itemKey = 0;
      for (const addressItem of addressList) {
        addressList[itemKey].province_name = yield _this.model('region').getRegionName(addressItem.province_id);
        addressList[itemKey].city_name = yield _this.model('region').getRegionName(addressItem.city_id);
        addressList[itemKey].district_name = yield _this.model('region').getRegionName(addressItem.district_id);
        addressList[itemKey].full_region = addressList[itemKey].province_name + addressList[itemKey].city_name + addressList[itemKey].district_name;
        itemKey += 1;
    }

      return _this.success(addressList);
    }

}