<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 12:46
 */

namespace App\Controllers;

use App\Models\Category;
use Slim\Http\Response;
use Slim\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request, Response $response)
    {

        $categoryId = $request->getParam('id');
        $categoryModel = new Category();
        $data = $categoryModel->where(['parent_id' => 0])->select()->toArray();

        $currentCategory = '';

        if ($categoryId) {
            $currentCategory = $categoryModel->where(['id' => $categoryId])->find()->toArray();
        }

        if (empty($currentCategory)) {
            $currentCategory = $data[0];
        }

        // 获取子分类数据
        if ($currentCategory && $currentCategory['id']) {
            $currentCategory['subCategoryList'] = $categoryModel->where(['parent_id' => $currentCategory['id']])->select()->toArray();
        }

        $response_data = [
            'categoryList' => $data,
            'currentCategory' => $currentCategory
        ];

        return $this->api_r(0, '', 200, $response_data, $response);
    }

    public function current(Request $request, Response $response)
    {
        $categoryId = $request->getParam('id');
        $categoryModel = new Category();

        $currentCategory = [];

        if ($categoryId) {
            $currentCategory = $categoryModel->where(['id' => $categoryId])->find()->toArray();
        }
        // 获取子分类数据
        if ($currentCategory && $currentCategory['id']) {
            $currentCategory['subCategoryList'] = $categoryModel->where(['parent_id' => $currentCategory['id']])->select()->toArray();
        }

        $response_data = [
            'currentCategory' => $currentCategory
        ];

        return $this->api_r(0, '', 200, $response_data, $response);
    }

}