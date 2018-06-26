<?php
namespace Admin\Model;
use Think\Model;
class ModuleModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('quote_id','fur_quo_id','cate_id','agio','space','fur_name');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('space','require','家具所在位置位置不能为空！',1),
           array('agio','0.8,1','折扣只能在0.8-1之间！',1,'between'),
    );
    public function check_number($open){
        $v = is_numeric ($open) ? true : false;
        return $v;
    }
    //添加之前
    public function _before_insert(&$data,$option){
        $quoteId = I('post.quote_id');
        $cateId = I('post.cate_id');

        $furQuoId = $data['fur_quo_id'];
        $furQuoModel = D('furniture_quote');
        $furQuoData = $furQuoModel->field('model_id')->find($furQuoId);

        $modelModel = D('model');
        $modelData = $modelModel->field('parameter')->find($furQuoData['model_id']);
        $modelParameter = json_decode($modelData['parameter']);
        $parameter = I('post.parameter');
        $parameterBeta = array();
        foreach ($modelParameter as $v){
            $parameterBeta[$v] = $parameter[$v];
        }


        $data['material'] = json_encode(I('post.material'));
        $data['parameter'] = json_encode($parameterBeta);
        $data['ext'] = json_encode(I('post.ext'));
        $num = $this->field('max(sort_id) as num')->where(array('quote_id'=>array('eq',$quoteId),'cate_id'=>array('eq',$cateId)))->group('quote_id')->select();
        if(empty($num)){
            $data['sort_id'] = 1;
        }else{
            $data['sort_id'] = $num[0]['num']+1;
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
    	
    }
    //删除之前
    public function _before_delete($option){

    }
    //报价单获取包含模块商品
    public function getInfo($quoteId){
        $cabinetFee = 0;
        $cabinetAgioFee = 0;
        $doorFee = 0;
        $doorAgioFee = 0;
        $frontFee = 0;
        $frontAgioFee = 0;
        $cabinetDetailFee = array();
        $doorDetailFee = array();
        $frontDetailFee = array();
        $cabinetData = array();
        $doorData = array();
        $frontData = array();
        //获取所有模块商品
        $data = $this->field('a.*,b.img_src,b.fur_attr_id,c.formula,c.project_area')
            ->alias('a')
            ->order('a.sort_id asc')
            ->join('LEFT JOIN __FURNITURE_QUOTE__ b ON a.fur_quo_id = b.id
                    LEFT JOIN __MODEL__ c ON b.model_id = c.id')
            ->where(array(
                'a.quote_id' => array('eq',$quoteId)
            ))
            ->select();

        foreach ($data as $k => $v){
            $extend = json_decode($v['ext'],true);
            foreach ($extend as $k0 => $v0){
                $$k0 = $v0;
            }

           $parameter = json_decode($v['parameter'],true);
            $parAttr = array();
           foreach ($parameter as $k1 => $v1){
               $$k1 = $v1;
               $parAttr[] = $k1.':'.$v1;
           }
           $parStr = implode('/',$parAttr);

           $parStrAttr = array();
            $parTolNum = count($parAttr);
           foreach ($parAttr as $k8 => $v8){
               if($k8%3 == 0){
                   $parOne = $v8.'/';
                   if($parTolNum == $k8+1){
                       $parOne = $v8;
                       $parStrAttr[] = $parOne;
                   }else{
                       $parOne = $v8.'/';
                   }
               }
               if($k8%3 == 1){
                   if($parTolNum == $k8+1){
                       $parOne = $parOne.$v8;
                       $parStrAttr[] = $parOne;
                   }else{
                       $parOne = $parOne.$v8.'/';
                   }
               }
               if($k8%3 == 2){
                   $parOne = $parOne.$v8;
                   $parStrAttr[] = $parOne;

               }
           }

            //var_dump($parStr);die;
           $material = json_decode($v['material'],true);

            foreach ($material as $k2 => $v2){
                $goodsModel = D('goods');
                if(is_array($v2)){
                    if($v2[1]){
                        $goodsData = $goodsModel->field('a.goods_name,b.goods_price as price')
                            ->alias('a')
                            ->where(array('a.id'=>array('eq',$v2[0])))
                            ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id AND b.id='.$v2[1])
                            ->find();
                    }else{
                        $goodsData = $goodsModel->field('a.goods_name,max(b.goods_price) as price')
                            ->alias('a')
                            ->group('b.goods_id')
                            ->where(array('a.id'=>array('eq',$v2[0])))
                            ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                            ->find();
                    }
                }else{
                    $goodsData = $goodsModel->field('a.goods_name,max(b.img_src) as img_src,max(goods_price) as price')
                        ->alias('a')
                        ->group('b.goods_id')
                        ->where(array('a.id'=>array('eq',$v2)))
                        ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                        ->find();
                    $goodsNumModel = D('goods_number');
                    $goodsNumData = $goodsNumModel->field('goods_price,img_src')->where(array('goods_id'=>array('eq',$v2)))->select();
                    foreach ($goodsNumData as $k3 => $v3){
                        if($v3['img_src'] == $goodsData['img_src']){
                            $goodsData['price'] = $v3['goods_price'];
                        }
                    }
                }
               $$k2 = $goodsData['price'];
               $goodsName[$k2] = $goodsData['goods_name'];
            }
            $module = array();
            $formula = json_decode($v['formula'],true);

            $projectArea = eval($v['project_area']);//投影面积

            if($v['cate_id'] == '2'){
                $furAttrId = explode(',',$v['fur_attr_id']);
                $mult = $furAttrId[1];
            }
            $totalModulePrice = 0;
            foreach ($formula as $k3 => $v3){
               $num = eval($v3[0]);
               $price = eval($v3[1]);
               $fee = eval($v3[3]);
               $agioFee = $fee * $v['agio'];
               if($v['agio'] === '1.00'){
                   $v['agio'] = 1;
               }
               if($v['cate_id'] == '1'){
                   $cabinetFee = $cabinetFee + $fee;
                   $cabinetAgioFee = $cabinetAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $cabinetDetailFee[$k3]['fee'] = $cabinetDetailFee[$k3]['fee'] + $fee;
                           $cabinetDetailFee[$k3]['agioFee'] = $cabinetDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $cabinetDetailFee[$k3]['fee'] = $fee;
                       $cabinetDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }elseif ($v['cate_id'] == '3'){
                   $frontFee = $frontFee + $fee;
                   $frontAgioFee = $frontAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $frontDetailFee[$k3]['fee'] = $frontDetailFee[$k3]['fee'] + $fee;
                           $frontDetailFee[$k3]['agioFee'] = $frontDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $frontDetailFee[$k3]['fee'] = $fee;
                       $frontDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }elseif ($v['cate_id'] == '2'){
                   $doorFee = $doorFee + $fee;
                   $doorAgioFee = $doorAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $doorDetailFee[$k3]['fee'] = $doorDetailFee[$k3]['fee'] + $fee;
                           $doorDetailFee[$k3]['agioFee'] = $doorDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $doorDetailFee[$k3]['fee'] = $fee;
                       $doorDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }

               if($v3[4]){
                   if($goodsName[$v3[4]]){
                       $remarkes = $goodsName[$v3[4]];
                   }else{
                       $remarkes = $v3[4];
                   }
               }else{
                   $remarkes = '-';
               }


                $module[] = array(
                 'name' => $k3,
                 'unit' => $v3[2],
                 'num'  => $num,
                 'price'=> $price,
                 'agio' => $v['agio'],
                 'agioFee' => $agioFee,
                 'remarkes' => $remarkes,

               );
               $totalModulePrice = $totalModulePrice + $agioFee;
            }
            if($v['cate_id'] == '1'){
                $cabinetData['module'][$k]['img_src'] = $v['img_src'];
                $cabinetData['module'][$k]['attr'] = explode(',',$v['attr']);
                $cabinetData['module'][$k]['project_area'] = $projectArea;
                $cabinetData['module'][$k]['formula'] = $module;
                $cabinetData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $cabinetData['module'][$k]['space'] = $v['space'];
                $cabinetData['module'][$k]['fur_name'] = $v['fur_name'];
                $cabinetData['module'][$k]['sort_id'] = $v['sort_id'];
                $cabinetData['module'][$k]['parameter'] = $parameter;
                $cabinetData['module'][$k]['parStr'] = $parStr;
                $cabinetData['module'][$k]['parStrAttr'] = $parStrAttr;
            }elseif ($v['cate_id'] == '3'){
                $frontData['module'][$k]['img_src'] = $v['img_src'];
                $frontData['module'][$k]['attr'] = explode(',',$v['attr']);
                $frontData['module'][$k]['project_area'] = $projectArea;
                $frontData['module'][$k]['formula'] = $module;
                $frontData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $frontData['module'][$k]['space'] = $v['space'];
                $frontData['module'][$k]['fur_name'] = $v['fur_name'];
                $frontData['module'][$k]['sort_id'] = $v['sort_id'];
                $frontData['module'][$k]['parameter'] = $parameter;
                $frontData['module'][$k]['parStr'] = $parStr;
                $frontData['module'][$k]['parStrAttr'] = $parStrAttr;
            }elseif ($v['cate_id'] == '2'){
                $doorData['module'][$k]['img_src'] = $v['img_src'];
                $doorData['module'][$k]['attr'] = explode(',',$v['attr']);
                $doorData['module'][$k]['project_area'] = $projectArea;
                $doorData['module'][$k]['formula'] = $module;
                $doorData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $doorData['module'][$k]['space'] = $v['space'];
                $doorData['module'][$k]['fur_name'] = $v['fur_name'];
                $doorData['module'][$k]['sort_id'] = $v['sort_id'];
                $doorData['module'][$k]['parameter'] = $parameter;
                $doorData['module'][$k]['parStr'] = $parStr;
                $doorData['module'][$k]['parStrAttr'] = $parStrAttr;
            }
        }
        $cabinetData['fee'] = $cabinetFee;
        $cabinetData['agioFee'] = $cabinetAgioFee;
        $cabinetData['detailFee'] = $cabinetDetailFee;
        $doorData['fee'] = $doorFee;
        $doorData['agioFee'] = $doorAgioFee;
        $doorData['detailFee'] = $doorDetailFee;
        $frontData['fee'] = $frontFee;
        $frontData['agioFee'] = $frontAgioFee;
        $frontData['detailFee'] = $frontDetailFee;
        unset($data);
        $moduledata = array(
            'cabinet' => $cabinetData,
            'door'    => $doorData,
            'front'   => $frontData,
        );
        return $moduledata;
    }
    //小程序添加新家具模块
    public function addWxModule(){
        $furId = I('get.furId');
        $furQuoId = I('get.furQuoId');
        $chooseCh = I('get.chooseCh');
        $extData = I('get.extData');
        $parData = I('get.parData');
        $chooseGoods = I('get.chooseGoods');
        $space = I('get.space');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        $extData = str_replace('&quot;','"',$extData);
        $parData = str_replace('&quot;','"',$parData);
        $chooseGoods = str_replace('&quot;','"',$chooseGoods);
        $chooseCh = str_replace('&quot;','"',$chooseCh);
        $extData = json_decode($extData,true);
        $parData = json_decode($parData,true);
        $chooseGoods = json_decode($chooseGoods,true);
        $chooseCh = json_decode($chooseCh,true);

        if(empty($chooseCh)){
            $attr = '默认';
        }else{
            $attr = implode(',',$chooseCh);
        }

        if($user){
            $quoteModel = D('quote');
            $furModel = D('furniture');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $furData = $furModel->field('fur_name,cate_id')->find($furId);


            $ext = array();
            foreach ($extData as $k => $v){
                $total = 0;
                foreach ($v as $k1 => $v1){
                    $total = $total + $v1;
                }
                $ext[$k] = $total;
            }

            $data = array(
                'quote_id' => $quoteId['id'],
                'fur_quo_id' => $furQuoId,
                'attr' => $attr,
                'cate_id' => $furData['cate_id'],
                'space' => $space,
                'fur_name' => $furData['fur_name'],
            );


            $_POST['quote_id'] = $quoteId['id'];
            $_POST['cate_id'] = $furData['cate_id'];
            $_POST['material'] = $chooseGoods;
            $_POST['parameter'] = $parData;
            $_POST['ext'] = $ext;

            $result = $this->add($data);
            if($result){
                return true;
            }else{
                return false;
            }

        }
    }
    //获取个人家具模块
    public function getModule(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $quoteModel = D('quote');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $data = $this->getInfo($quoteId['id']);
            return $data;
        }
    }

    //删除模块
    public function delModule(){
        $sortId = I('get.sort');
        $cateId = I('get.cat');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $quoteModel = D('quote');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $result = $this->where(array(
                'quote_id' => array('eq',$quoteId['id']),
                'cate_id' => array('eq',$cateId),
                'sort_id' => array('eq',$sortId),
            ))->delete();
            if($result){
                $data = $this->where(array(
                    'quote_id' => array('eq',$quoteId['id']),
                    'cate_id' => array('eq',$cateId),
                ))->select();
                foreach ($data as $k => $v){
                    $saveData = array(
                        'sort_id' => $k+1,
                    );
                    $where = array(
                        'quote_id' => array('eq',$v['quote_id']),
                        'cate_id' => array('eq',$v['cate_id']),
                        'sort_id' => array('eq',$v['sort_id']),
                    );
                    $this->where($where)->save($saveData);
                }
                return true;
            }else{
                return false;
            }
        }
    }
    public function getExcel($quoteId,$isDown=false){
        $data = $this->getInfo($quoteId);
        $quoModel = D('quote');
        $quoData = $quoModel->find($quoteId);
        import("Org.PHPExcel.PHPExcel");
        import("Org.PHPExcel.PHPExcel.IOFactory", '', '.php');
        import("Org.PHPExcel.PHPExcel.PHPExcel_Style_Alignment", '', '.php');
        $obj = new \PHPExcel();

        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        //边框样式
        $styleArrayThin = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => \PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $index = 0;

        if(!empty($data['cabinet']['detailFee'])){
            $obj->createSheet();
            $obj->setactivesheetIndex($index)->setTitle('柜体报价');   //设置sheet名称

            $_row = 1;   //设置纵向单元格标识
            $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.$cellName[7].$_row);   //合并单元格
            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '柜体报价');  //设置合并后的单元格内容
            $obj->getActiveSheet($index)->getRowDimension('1')->setRowHeight(40);
            $obj->getActiveSheet($index)->getStyle('A1:H2')->applyFromArray($styleArrayThin);
            $obj->getActiveSheet($index)->getStyle('E3:H4')->applyFromArray($styleArrayThin);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目名称：');  //设置项目名称标题单元格
            $obj->getActiveSheet($index)->mergeCells('B'.$_row.':'.'D'.$_row);   //合并项目名称单元格
            $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, $quoData['address']);  //设置项目名称单元格

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '客户姓名：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $quoData['user_name']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '联系方式：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $quoData['telephone']);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '报价人：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '系统报价');
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '报价时间：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, date('Y/m/d',time()));
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '项目总价：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $data['cabinet']['fee']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '折后：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $data['cabinet']['agioFee']);
            $_row++;

            $minrow = $_row;
            $j = 0;
            foreach ($data['cabinet']['detailFee'] as $k0 => $v0){
                if($j >7){
                    $j = 0;
                    $_row++;
                }
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $k0);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['fee']);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, '折后');
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['agioFee']);
                $j++;
            }
            $_row++;
            $maxrow = $_row-1;
            $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

            foreach ($data['cabinet']['module'] as $k => $v){
                $__row = $_row+1;
                $minrow = $_row;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'A'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '产品信息：');
                $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, '序号');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '产品位置');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '产品类型');
                $obj->getActiveSheet($index)->mergeCells('E'.$_row.':'.'G'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '规格');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '投影面积');

                $obj->setActiveSheetIndex($index)->setCellValue('B'.$__row, 'G'.$v['sort_id']);
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$__row, $v['space']);
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$__row, $v['fur_name']);
                $obj->getActiveSheet($index)->mergeCells('E'.$__row.':'.'G'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$__row, $v['parStr']);
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$__row, $v['project_area']);

                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->getStartColor()->setARGB('ffff00');//设置填充颜色

                $_row += 2;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'B'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '单位');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '数量');
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '单价(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '折扣');
                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '金额(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '备注');

                $_row++;
                foreach($v['formula'] as $k1 => $v1){
                    $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row,$v1['name']);
                    $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row,$v1['unit']);
                    $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row,$v1['num']);
                    $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row,$v1['price']);
                    $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row,$v1['agio']);
                    $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v1['agioFee']);
                    $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row,$v1['remarkes']);

                    $_row++;
                }
                $maxrow = $_row-1;

                $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v['totalModulePrice']);
                $_row++;
            }
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居中
            $obj->setActiveSheetIndex($index)->getDefaultColumnDimension()->setWidth(13);

            $index++;
        }

        //门板
        if(!empty($data['door']['detailFee'])){
            $obj->createSheet();
            $obj->setactivesheetIndex($index)->setTitle('门控报价');   //设置sheet名称

            $_row = 1;   //设置纵向单元格标识
            $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.$cellName[7].$_row);   //合并单元格
            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '门控报价');  //设置合并后的单元格内容
            $obj->getActiveSheet($index)->getRowDimension('1')->setRowHeight(40);
            $obj->getActiveSheet($index)->getStyle('A1:H2')->applyFromArray($styleArrayThin);
            $obj->getActiveSheet($index)->getStyle('E3:H4')->applyFromArray($styleArrayThin);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目名称：');  //设置项目名称标题单元格
            $obj->getActiveSheet($index)->mergeCells('B'.$_row.':'.'D'.$_row);   //合并项目名称单元格
            $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, $quoData['address']);  //设置项目名称单元格

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '客户姓名：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $quoData['user_name']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '联系方式：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $quoData['telephone']);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '报价人：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '系统报价');
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '报价时间：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, date('Y/m/d',time()));
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '项目总价：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $data['door']['fee']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '折后：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $data['door']['agioFee']);
            $_row++;

            $minrow = $_row;
            $j = 0;
            foreach ($data['door']['detailFee'] as $k0 => $v0){
                if($j >7){
                    $j = 0;
                    $_row++;
                }
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $k0);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['fee']);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, '折后');
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['agioFee']);
                $j++;
            }
            $_row++;
            $maxrow = $_row-1;
            $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

            foreach ($data['door']['module'] as $k => $v){
                $__row = $_row+1;
                $minrow = $_row;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'A'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '产品信息：');
                $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, '序号');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '产品位置');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '产品类型');
                $obj->getActiveSheet($index)->mergeCells('E'.$_row.':'.'G'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '规格');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '投影面积');

                $obj->setActiveSheetIndex($index)->setCellValue('B'.$__row, 'D'.$v['sort_id']);
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$__row, $v['space']);
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$__row, $v['fur_name']);
                $obj->getActiveSheet($index)->mergeCells('E'.$__row.':'.'G'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$__row, $v['parStr']);
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$__row, $v['project_area']);

                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->getStartColor()->setARGB('ffff00');//设置填充颜色

                $_row += 2;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'B'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '单位');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '数量');
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '单价(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '折扣');
                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '金额(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '备注');

                $_row++;
                foreach($v['formula'] as $k1 => $v1){
                    $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row,$v1['name']);
                    $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row,$v1['unit']);
                    $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row,$v1['num']);
                    $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row,$v1['price']);
                    $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row,$v1['agio']);
                    $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v1['agioFee']);
                    $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row,$v1['remarkes']);

                    $_row++;
                }
                $maxrow = $_row-1;

                $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v['totalModulePrice']);
                $_row++;
            }
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居中
            $obj->setActiveSheetIndex($index)->getDefaultColumnDimension()->setWidth(13);

            $index++;
        }

        //饰面报价
        if(!empty($data['front']['detailFee'])){
            $obj->createSheet();
            $obj->setactivesheetIndex($index)->setTitle('饰面报价');   //设置sheet名称

            $_row = 1;   //设置纵向单元格标识
            $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.$cellName[7].$_row);   //合并单元格
            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '饰面报价');  //设置合并后的单元格内容
            $obj->getActiveSheet($index)->getRowDimension('1')->setRowHeight(40);
            $obj->getActiveSheet($index)->getStyle('A1:H2')->applyFromArray($styleArrayThin);
            $obj->getActiveSheet($index)->getStyle('E3:H4')->applyFromArray($styleArrayThin);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目名称：');  //设置项目名称标题单元格
            $obj->getActiveSheet($index)->mergeCells('B'.$_row.':'.'D'.$_row);   //合并项目名称单元格
            $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, $quoData['address']);  //设置项目名称单元格

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '客户姓名：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $quoData['user_name']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '联系方式：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $quoData['telephone']);
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '报价人：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '系统报价');
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '报价时间：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, date('Y/m/d',time()));
            $_row++;

            $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '项目总价：');
            $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, $data['front']['fee']);
            $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '折后：');
            $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, $data['front']['agioFee']);
            $_row++;

            $minrow = $_row;
            $j = 0;
            foreach ($data['front']['detailFee'] as $k0 => $v0){
                if($j >7){
                    $j = 0;
                    $_row++;
                }
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $k0);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['fee']);
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, '折后');
                $j++;
                $obj->setActiveSheetIndex($index)->setCellValue($cellName[$j].$_row, $v0['agioFee']);
                $j++;
            }
            $_row++;
            $maxrow = $_row-1;
            $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

            foreach ($data['front']['module'] as $k => $v){
                $__row = $_row+1;
                $minrow = $_row;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'A'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '产品信息：');
                $obj->setActiveSheetIndex($index)->setCellValue('B'.$_row, '序号');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '产品位置');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '产品类型');
                $obj->getActiveSheet($index)->mergeCells('E'.$_row.':'.'G'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '规格');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '投影面积');

                $obj->setActiveSheetIndex($index)->setCellValue('B'.$__row, 'S'.$v['sort_id']);
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$__row, $v['space']);
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$__row, $v['fur_name']);
                $obj->getActiveSheet($index)->mergeCells('E'.$__row.':'.'G'.$__row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$__row, $v['parStr']);
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$__row, $v['project_area']);

                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
                $obj->getActiveSheet($index)->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->getStartColor()->setARGB('ffff00');//设置填充颜色

                $_row += 2;
                $obj->getActiveSheet($index)->mergeCells('A'.$_row.':'.'B'.$_row);   //合并单元格
                $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row, '项目');
                $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row, '单位');
                $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row, '数量');
                $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row, '单价(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row, '折扣');
                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row, '金额(元)');
                $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row, '备注');

                $_row++;
                foreach($v['formula'] as $k1 => $v1){
                    $obj->setActiveSheetIndex($index)->setCellValue('A'.$_row,$v1['name']);
                    $obj->setActiveSheetIndex($index)->setCellValue('C'.$_row,$v1['unit']);
                    $obj->setActiveSheetIndex($index)->setCellValue('D'.$_row,$v1['num']);
                    $obj->setActiveSheetIndex($index)->setCellValue('E'.$_row,$v1['price']);
                    $obj->setActiveSheetIndex($index)->setCellValue('F'.$_row,$v1['agio']);
                    $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v1['agioFee']);
                    $obj->setActiveSheetIndex($index)->setCellValue('H'.$_row,$v1['remarkes']);

                    $_row++;
                }
                $maxrow = $_row-1;

                $obj->getActiveSheet($index)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

                $obj->setActiveSheetIndex($index)->setCellValue('G'.$_row,$v['totalModulePrice']);
                $_row++;
            }
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
            $obj->setActiveSheetIndex($index)->getStyle('A1:H'.$_row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居中
            $obj->setActiveSheetIndex($index)->getDefaultColumnDimension()->setWidth(13);

            $index++;
        }

        $fileName = $quoData['address'];

        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

        if($isDown){   //网页下载
            header('pragma:public');
            header("Content-Disposition:attachment;filename=$fileName.xls");
            $objWrite->save('php://output');exit;
        }
        $savePath = APP_PATH.'../Public/Excel/';
        $fileName = date('YmdHis',time()).rand().'.xlsx';
        $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
        $_savePath = $savePath.$_fileName;
        $objWrite->save($_savePath);
        return $fileName;

    }

}