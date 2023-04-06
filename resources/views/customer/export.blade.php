@php
    base_path('vendor/autoload.php');

    $arr_az = range('A','Z');
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $excel->getActiveSheet();;

        $noBarang = 8;
        $noQty = 8;
        $noHarga = 8;
        $noTotal = 8;
        $styleHarga = 8;
        $styleTotal = 8;
       
        foreach ($data as $index => $sale)
        {
            foreach (range('A', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $excel->getActiveSheet()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
            } 
            foreach (range('B', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $excel->getActiveSheet()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
            } 
            foreach (range('C', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $excel->getActiveSheet()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
            } 
            foreach (range('D', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $excel->getActiveSheet()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
            } 

            $invoicedata = $sale["invoice"];
            $namadata = $sale["nama"];
            $tanggal = date('d-m-Y', strtotime($sale['tanggal']));
            $tgldata = $sale["tanggal"] = $tanggal;
            $jeniskelamindata = $sale["jeniskelamin"];
            $saldodata = $sale["saldo"];
            $datakosong = '';

            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ];
            $styleArray2 = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                'font' => [
                    'bold' => true,
                ],
            ];
            $styleArray3 = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                ],
                'font' => [
                    'bold' => true,
                ],
            ];
            $styleArray4 = [
                
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ];
            $sheet->getStyle('A1')->applyFromArray($styleArray);
            $sheet->getStyle('A2')->applyFromArray($styleArray);
            $sheet->getStyle('A3')->applyFromArray($styleArray);
            $sheet->getStyle('A4')->applyFromArray($styleArray);
            $sheet->getStyle('A5')->applyFromArray($styleArray);
            $sheet->getStyle('A6')->applyFromArray($styleArray);
            $sheet->getStyle('A7')->applyFromArray($styleArray2);
            $sheet->getStyle('B7')->applyFromArray($styleArray2);
            $sheet->getStyle('C7')->applyFromArray($styleArray2);
            $sheet->getStyle('D7')->applyFromArray($styleArray2);
            
            $sheet->setCellValue('A1','No.Invoice');
            $sheet->setCellValue('A2','Customer Name');
            $sheet->setCellValue('A3','Date');
            $sheet->setCellValue('A4','Gender');
            $sheet->setCellValue('A5','Saldo');
            $sheet->setCellValue('A6','');
            $sheet->setCellValue('A7','Item Name');
            $sheet->setCellValue('B7','Quantity');
            $sheet->setCellValue('C7','Item Price');
            $sheet->setCellValue('D7','Total Price');

            $sheet->setCellValue('B1',':');
            $sheet->setCellValue('B2',':');
            $sheet->setCellValue('B3',':');
            $sheet->setCellValue('B4',':');
            $sheet->setCellValue('B5',':');
            $sheet->setCellValue('B6','');
            
            $sheet->setCellValue('C1',$invoicedata);
            $sheet->setCellValue('C2',$namadata);
            $sheet->setCellValue('C3',$tgldata);
            $sheet->setCellValue('C4',$jeniskelamindata);
            $sheet->setCellValue('C5',$saldodata);
            $sheet->setCellValue('C6',$datakosong);
            $sheet->getStyle('C1')->applyFromArray($styleArray4);
            $sheet->getStyle('C5',$saldodata)->getNumberFormat()->setFormatCode("Rp #,##0.00");
            $sheet->getStyle('C3',$tgldata)->getNumberFormat()->setFormatCode('dd-mm-yyyy');

            foreach($detail as $index => $dataDetail)
            {
                $brgdata = $dataDetail["namabarang"];
                $qtydata = $dataDetail["qty"];
                $hargadata = $dataDetail["harga"];

                $sheet->setCellValue($arr_az[0].$noBarang++,$brgdata);
                
                $qtyCell = $arr_az[1].$noQty++;
                $sheet->setCellValue($qtyCell,$qtydata);

                $hargaCell = $arr_az[2].$noHarga++;
                $sheet->setCellValue($hargaCell,$hargadata);
                $sheet->getStyle($arr_az[2].$styleHarga++,$saldodata)->getNumberFormat()->setFormatCode("Rp #,##0.00");
            
                $totalCell = $arr_az[3].($noTotal++);
                $sheet->setCellValue($totalCell, '='.$qtyCell.'*'.$hargaCell.'');
                $sheet->getStyle($arr_az[3].$styleTotal++,'='.$qtyCell.'*'.$hargaCell.'')->getNumberFormat()->setFormatCode("Rp #,##0.00");
            }
            $sheet->setCellValue($arr_az[2].($noTotal+1),'Total'); 
            $sheet->getStyle($arr_az[2].($noTotal+1))->applyFromArray($styleArray);

            $sheet->setCellValue($arr_az[3].($noTotal+1), '=SUM(D8:D'.($noTotal-1).')');
            $sheet->getStyle($arr_az[3].($noTotal+1))->applyFromArray($styleArray3);
            $sheet->getStyle($arr_az[3].($noTotal+1))->getNumberFormat()->setFormatCode("Rp #,##0.00");
        }
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="filename_' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');

        $xlsxWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
        $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $xlsxWriter->save('php://output');
@endphp