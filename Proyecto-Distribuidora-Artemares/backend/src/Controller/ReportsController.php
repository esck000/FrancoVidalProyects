<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Laminas\Diactoros\Stream;


class ReportsController extends AppController
{
    public function salesByProduct()
    {
        $from = FrozenTime::now()->subDays(7);

        $data = $this->fetchTable('OrdersProducts')
            ->getSalesByProduct($from);

        $ext = $this->request->getParam('_ext');

        if ($ext === 'csv') {
            return $this->exportCsv($data);
        }

        if ($ext === 'xlsx') {
            return $this->exportExcel($data);
        }

        // SOLO si no es exportación
        $this->set(compact('data'));
    }

    public function salesByProduct30()
    {
        $from = FrozenTime::now()->subDays(30);

        $data = $this->fetchTable('OrdersProducts')
            ->getSalesByProduct($from);

        $ext = $this->request->getParam('_ext');

        if ($ext === 'csv') {
            return $this->exportCsv($data);
        }

        if ($ext === 'xlsx') {
            return $this->exportExcel($data);
        }

        // SOLO si no es exportación
        $this->set(compact('data'));
    }

    private function exportCsv(array $data)
    {
        $filename = 'ventas_por_producto_7_dias.csv';

        $this->response = $this->response
            ->withType('csv')
            ->withDownload($filename);

        $output = fopen('php://output', 'w');

        // Encabezados
        fputcsv($output, ['Producto', 'Cantidad Vendida', 'Total ($)']);

        // Filas
        foreach ($data as $row) {
            fputcsv($output, [
                $row['product_name'],
                $row['total_quantity'],
                $row['total_amount'],
            ]);
        }

        fclose($output);

        return $this->response;
    }


    private function exportExcel(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->fromArray(
            ['Producto', 'Cantidad Vendida', 'Total ($)'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $item['product_name']);
            $sheet->setCellValue("B{$row}", $item['total_quantity']);
            $sheet->setCellValue("C{$row}", $item['total_amount']);
            $row++;
        }

        // guardar Excel en un archivo temporal
        $tmpFile = TMP . 'ventas_por_producto_7_dias.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpFile);

        // crear stream para la response
        $stream = new Stream($tmpFile, 'r');

        return $this->response
            ->withType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->withBody($stream)
            ->withDownload('ventas_por_producto_7_dias.xlsx');
    }

    private function exportExcel30(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->fromArray(
            ['Producto', 'Cantidad Vendida', 'Total ($)'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $item['product_name']);
            $sheet->setCellValue("B{$row}", $item['total_quantity']);
            $sheet->setCellValue("C{$row}", $item['total_amount']);
            $row++;
        }

        // guardar Excel en un archivo temporal
        $tmpFile = TMP . 'ventas_por_producto_30_dias.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpFile);

        // crear stream para la response
        $stream = new Stream($tmpFile, 'r');

        return $this->response
            ->withType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->withBody($stream)
            ->withDownload('ventas_por_producto_30_dias.xlsx');
    }

}
