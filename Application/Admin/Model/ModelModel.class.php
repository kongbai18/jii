<?php
namespace Admin\Model;
use Think\Model;
class ModelModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('model_name','project_area');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','model_name','project_area');
    //验证码规则
    protected $_validate = array(
           array('model_name','require','模型名称不能为空！',1),
           array('project_area','require','投影面积公式不能为空！',1),
    );
    //添加之前
    public function _before_insert(&$data,$option){
        $materialPrice = I('post.material-price');
        $materialName = I('post.material-name');
        $materialVal = I('post.material-val');
        $parameter = I('post.parameter');
        $formulaName = I('post.formula-name');
        $formulaNum = I('post.formula-num');
        $formulaPrice = I('post.formula-price');
        $formulaTotalPrice = I('post.formula-total-price');
        $formulaUnit = I('post.formula-unit');
        $formulaRemarke = I('post.formula-remarke');
        $extCat = I('post.ext_cat');
        $extName = I('post.ext_name');
        $extPara = I('post.ext_para');
        $extValName = I('post.ext_val_name');
        $extVal = I('post.ext_val');

        $material = array();
        foreach ($materialPrice as $k => $v){
            $maVal = array_unique($materialVal[$k]);
            $maVal = implode(',',$maVal);
            $material[$v] = array($materialName[$k] => $maVal);
        }
        $formula = array();
        foreach ($formulaName as $k => $v){
            $formula[$v] = array($formulaNum[$k],$formulaPrice[$k],$formulaUnit[$k],$formulaTotalPrice[$k],$formulaRemarke[$k]);
        }

        $extend = array();
        $index = 0;
        foreach ($extCat as $k => $v){
            $extend[$index][$v][0] = $extName[$k];
            $extend[$index][$v][1] = $extPara[$k];
            if($v == '1' || $v == '2'){
                foreach ($extValName[$k] as $k1 => $v1){
                    $extend[$index][$v][2][$v1] = $extVal[$k][$k1];
                }
            }
            $index = $index + 1;
        }

        $material = json_encode($material);
        $formula = json_encode($formula);
        $parameter = json_encode($parameter);
        $extend = json_encode($extend);
        $data['material'] = $material;
        $data['parameter'] = $parameter;
        $data['formula'] = $formula;
        $data['ext'] = $extend;



    }
    //修改之前
    public function _before_update(&$data,$option){
        $materialPrice = I('post.material-price');
        $materialName = I('post.material-name');
        $materialVal = I('post.material-val');
        $parameter = I('post.parameter');
        $formulaName = I('post.formula-name');
        $formulaNum = I('post.formula-num');
        $formulaPrice = I('post.formula-price');
        $formulaTotalPrice = I('post.formula-total-price');
        $formulaUnit = I('post.formula-unit');
        $formulaRemarke = I('post.formula-remarke');
        $extCat = I('post.ext_cat');
        $extName = I('post.ext_name');
        $extPara = I('post.ext_para');
        $extValName = I('post.ext_val_name');
        $extVal = I('post.ext_val');
        //var_dump($_POST);die;
        $material = array();
        foreach ($materialPrice as $k => $v){
            $maVal = array_unique($materialVal[$k]);
            $maVal = implode(',',$maVal);
            $material[$v] = array($materialName[$k] => $maVal);
        }
        $formula = array();
        foreach ($formulaName as $k => $v){
            $formula[$v] = array($formulaNum[$k],$formulaPrice[$k],$formulaUnit[$k],$formulaTotalPrice[$k],$formulaRemarke[$k]);
        }

        $extend = array();
        $index = 0;
        foreach ($extCat as $k => $v){
            $extend[$index][$v][0] = $extName[$k];
            $extend[$index][$v][1] = $extPara[$k];
            if($v == '1' || $v == '2'){
                foreach ($extValName[$k] as $k1 => $v1){
                    $extend[$index][$v][2][$v1] = $extVal[$k][$k1];
                }
            }
            $index = $index + 1;
        }

        $material = json_encode($material);
        $formula = json_encode($formula);
        $parameter = json_encode($parameter);
        $extend = json_encode($extend);
        $data['material'] = $material;
        $data['parameter'] = $parameter;
        $data['formula'] = $formula;
        $data['ext'] = $extend;
    }
    //删除之前
    public function _before_delete($option){

    }
    //验证模型是否存在
    public function _checkModel($furId,$gatAttr){
        $furQuoModel = D('furniture_quote');
        $furQuoData = $furQuoModel->field('id,model_id,img_src')->where(array(
            'fur_attr_id' => array('eq',$gatAttr),
            'fur_id' => array('eq',$furId)
        ))->select();
        if(empty($furQuoData)){
            return false;
        }else{
            $data = array(
                'fur' => $furQuoData,
                'gat' => $gatAttr,
            );
            return $data;
        }
    }
    public function checkModel($furId,$gat){
        $gat = explode(',',$gat);
        $gat[1] = 0;

        for ($gat[1];$gat[1]<7;$gat[1]++){
            $gatAttr = implode(',',$gat);
            $result = $this->_checkModel($furId,$gatAttr);
            if($result){
                return $result;
            }
        }
        return false;
    }
    //小程序获取计价模型
    public function getFurModel(){
        $gatAttr = I('get.gatAttr');
        $furId = I('get.furId');
        $gatAttr = rtrim($gatAttr,',');
        $furQuoModel = D('furniture_quote');
        $furQuoData = $furQuoModel->field('id,model_id,img_src')->where(array(
            'fur_attr_id' => array('eq',$gatAttr),
            'fur_id' => array('eq',$furId)
        ))->select();

        if(empty($furQuoData)){
            $resData = $this->checkModel($furId,$gatAttr);
            if($resData){
                $furQuoData = $resData['fur'];
                $gatAttr = $resData['gat'];
            }else{
                $furQuoData = array();
            }
        }

        if(empty($furQuoData)){
            return false;
        }else {
            $modelData = $this->find($furQuoData[0]['model_id']);
            $material = json_decode($modelData['material'], true);
            $goodsModel = D('goods');
            foreach ($material as $k => &$v) {
                foreach ($v as $k1 => &$v1) {
                    $cateId = explode(',', $v1);
                    $goodsData = $goodsModel->field('a.id,a.goods_name,max(b.img_src) as img_src')
                        ->alias('a')
                        ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                        ->order('a.sort_id asc')
                        ->where(array('a.cat_id' => array('in', $cateId), 'a.is_quote' => array('eq', '1')))
                        ->group('a.id')
                        ->select();
                    $v1 = $goodsData;
                }
            }
            $parameter = json_decode($modelData['parameter'], true);
            $ext = json_decode($modelData['ext'], true);
            unset($modelData['material']);
            unset($modelData['model_name']);
            unset($modelData['formula']);
            unset($modelData['parameter']);

            $gatAttr = explode(',', $gatAttr);
            $addData = '';
            $minData = '';
            $minVal = '';
            if ($gatAttr[1] || ($gatAttr[1] == 0)) {
                $minVal = $gatAttr[1];
                $gatAttr[1] = $gatAttr[1] - 1;
                $minGatAttr = implode(',', $gatAttr);
                $gatAttr[1] = $gatAttr[1] + 2;
                $addGatAttr = implode(',', $gatAttr);
                $minFurQuoData = $furQuoModel->field('id,model_id,img_src')->where(array(
                    'fur_attr_id' => array('eq', $minGatAttr),
                    'fur_id' => array('eq', $furId)
                ))->select();
                if (empty($minFurQuoData)) {
                    $minData = '2';
                } else {
                    $minData = '1';
                }
                $addFurQuoData = $furQuoModel->field('id,model_id,img_src')->where(array(
                    'fur_attr_id' => array('eq', $addGatAttr),
                    'fur_id' => array('eq', $furId)
                ))->select();
                if (empty($addFurQuoData)) {
                    $addData = '2';
                } else {
                    $addData = '1';
                }
            }

            $data = array(
                'furQuoId' => $furQuoData[0]['id'],
                'furQuoImg' => $furQuoData[0]['img_src'],
                'modelData' => $modelData,
                'material' => $material,
                'parameter' => $parameter,
                'ext' => $ext,
                'minData' => $minData,
                'addData' => $addData,
                'minVal' => $minVal,
            );
            return $data;
        }

    }

    public function test(){
          $moduleModel = D('Admin/module');
          $data = $moduleModel->getInfo('20180530170511205737');
        //var_dump($data['cabinet']);die;
        $fileName='学生表';
        $savePath='./';
        $title=array('姓名','班级','年龄');
        import("Org.PHPExcel.PHPExcel");
        import("Org.PHPExcel.PHPExcel.IOFactory", '', '.php');
        import("Org.PHPExcel.PHPExcel.PHPExcel_Style_Alignment", '', '.php');
        $obj = new \PHPExcel();
        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $styleArrayThin = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => \PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $obj->getActiveSheet(0)->setTitle('柜体报价');   //设置sheet名称
        $obj->createSheet();
        $obj->setactivesheetindex(1)->setTitle('门板报价');   //设置sheet名称
        $obj->createSheet();
        $obj->setactivesheetindex(2)->setTitle('饰面报价');   //设置sheet名称

        $_row = 1;   //设置纵向单元格标识
        if($title){
            $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[7].$_row);   //合并单元格
            $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '柜体报价');  //设置合并后的单元格内容
            $obj->getActiveSheet(0)->getRowDimension('1')->setRowHeight(40);
            $obj->getActiveSheet(0)->getStyle('A1:H2')->applyFromArray($styleArrayThin);
            $obj->getActiveSheet(0)->getStyle('E3:H4')->applyFromArray($styleArrayThin);
            $_row++;

            $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '项目名称：');  //设置项目名称标题单元格
            $obj->getActiveSheet(0)->mergeCells('B'.$_row.':'.'D'.$_row);   //合并项目名称单元格
            $obj->setActiveSheetIndex(0)->setCellValue('B'.$_row, '');  //设置项目名称单元格

            $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row, '客户姓名：');
            $obj->setActiveSheetIndex(0)->setCellValue('F'.$_row, '');
            $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row, '联系方式：');
            $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row, '');
            $_row++;

            $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row, '报价人：');
            $obj->setActiveSheetIndex(0)->setCellValue('F'.$_row, '系统报价');
            $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row, '报价时间：');
            $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row, date('Y/m/d',time()));
            $_row++;

            $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row, '项目总价：');
            $obj->setActiveSheetIndex(0)->setCellValue('F'.$_row, $data['cabinet']['fee']);
            $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row, '折后：');
            $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row, $data['cabinet']['agioFee']);
            $_row++;

            $minrow = $_row;
            $j = 0;
            foreach ($data['cabinet']['detailFee'] as $k0 => $v0){
                if($j >7){
                    $j = 0;
                    $_row++;
                }
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$j].$_row, $k0);
                $j++;
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$j].$_row, $v0['fee']);
                $j++;
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$j].$_row, '折扣');
                $j++;
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$j].$_row, $v0['agioFee']);
                $j++;
            }
            $_row++;
            $maxrow = $_row-1;
            $obj->getActiveSheet(0)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

            foreach ($data['cabinet']['module'] as $k => $v){
                $__row = $_row+1;
                $minrow = $_row;
                $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.'A'.$__row);   //合并单元格
                $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '产品信息：');
                $obj->setActiveSheetIndex(0)->setCellValue('B'.$_row, '序号');
                $obj->setActiveSheetIndex(0)->setCellValue('C'.$_row, '产品位置');
                $obj->setActiveSheetIndex(0)->setCellValue('D'.$_row, '产品类型');
                $obj->getActiveSheet(0)->mergeCells('E'.$_row.':'.'G'.$_row);   //合并单元格
                $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row, '规格');
                $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row, '投影面积');

                $obj->setActiveSheetIndex(0)->setCellValue('B'.$__row, 'G'.$v['sort_id']);
                $obj->setActiveSheetIndex(0)->setCellValue('C'.$__row, $v['space']);
                $obj->setActiveSheetIndex(0)->setCellValue('D'.$__row, $v['fur_name']);
                $obj->getActiveSheet(0)->mergeCells('E'.$__row.':'.'G'.$__row);   //合并单元格
                $obj->setActiveSheetIndex(0)->setCellValue('E'.$__row, $v['parStr']);
                $obj->setActiveSheetIndex(0)->setCellValue('H'.$__row, $v['project_area']);

                $obj->getActiveSheet()->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
                $obj->getActiveSheet()->getStyle('A'.$_row.':'.'H'.$__row)->getFill()->getStartColor()->setARGB('ffff00');//设置填充颜色

                $_row += 2;
                $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.'B'.$_row);   //合并单元格
                $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '项目');
                $obj->setActiveSheetIndex(0)->setCellValue('C'.$_row, '单位');
                $obj->setActiveSheetIndex(0)->setCellValue('D'.$_row, '数量');
                $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row, '单价(元)');
                $obj->setActiveSheetIndex(0)->setCellValue('F'.$_row, '折扣');
                $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row, '金额(元)');
                $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row, '备注');

                $_row++;
                foreach($v['formula'] as $k1 => $v1){
                    $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row,$v1['name']);
                    $obj->setActiveSheetIndex(0)->setCellValue('C'.$_row,$v1['unit']);
                    $obj->setActiveSheetIndex(0)->setCellValue('D'.$_row,$v1['num']);
                    $obj->setActiveSheetIndex(0)->setCellValue('E'.$_row,$v1['price']);
                    $obj->setActiveSheetIndex(0)->setCellValue('F'.$_row,$v1['agio']);
                    $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row,$v1['agioFee']);
                    $obj->setActiveSheetIndex(0)->setCellValue('H'.$_row,$v1['remarkes']);

                    $_row++;
                }
                $maxrow = $_row-1;

                $obj->getActiveSheet(0)->getStyle('A'.$minrow.':'.'H'.$maxrow)->applyFromArray($styleArrayThin);

                $obj->setActiveSheetIndex(0)->setCellValue('G'.$_row,$v['totalModulePrice']);
                $_row++;

            }
            $obj->getActiveSheet(0)->getStyle('A1:H'.$_row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
            $obj->getActiveSheet(0)->getStyle('A1:H'.$_row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居中
            $obj->getActiveSheet(0)->getDefaultColumnDimension()->setWidth(13);

            //文件名处理
            if(!$fileName){
                $fileName = uniqid(time(),true);
            }

            $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
            $isDown = true;
            if($isDown){   //网页下载
                header('pragma:public');
                header("Content-Disposition:attachment;filename=$fileName.xls");
                $objWrite->save('php://output');exit;
            }

            $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
            $_savePath = $savePath.$_fileName.'.xlsx';
            $objWrite->save($_savePath);

        }

    }

}