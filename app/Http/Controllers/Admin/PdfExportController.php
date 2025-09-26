<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfExportController extends Controller
{
    public function exportTable(Request $request)
    {
        $tableData = $request->input('tableData');
        $title = $request->input('title', 'تقرير');
        
        // Parse table data to extract text content
        $dom = new \DOMDocument();
        @$dom->loadHTML('<meta charset="UTF-8">' . $tableData);
        
        $headers = [];
        $rows = [];
        
        // Extract headers
        $headerNodes = $dom->getElementsByTagName('th');
        foreach ($headerNodes as $header) {
            $headers[] = trim($header->textContent);
        }
        
        // Extract rows
        $rowNodes = $dom->getElementsByTagName('tr');
        foreach ($rowNodes as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length > 0) {
                $rowData = [];
                foreach ($cells as $cell) {
                    $rowData[] = trim($cell->textContent);
                }
                $rows[] = $rowData;
            }
        }
        
        $html = view('admin.pdf.table', compact('headers', 'rows', 'title'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="' . $title . '.html"');
    }
}