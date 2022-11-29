<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 *
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        MIT License
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */

use Dompdf\Dompdf;

class CreatePdf extends Dompdf
{
    /**
     * PDF filename
     * @var String
     */
    public $filename;
    public $path;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->filename = "laporan.pdf";
        $this->path = "";
    }
    /**
     * Get an instance of CodeIgniter
     *
     * @access    protected
     * @return    void
     */
    protected function ci()
    {
        return get_instance();
    }
    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access    public
     * @param    string    $view The view to load
     * @param    array    $data The view data
     * @return    void
     */
    public function load_view($view, $data = array())
    {
        $html = $this->ci()->load->view($view, $data, TRUE);
        
        $this->load_html($html);

        // Render the PDF
        $this->render();

        // Output the generated PDF to Browser
        // $output = $this->stream($this->filename, array("Attachment" => false));

        $output = $this->output();
        
        $pdfFilePath = FCPATH . $this->path . $this->filename;
        
        if (file_exists($pdfFilePath)) {unlink($pdfFilePath);}

        file_put_contents($pdfFilePath, $output);

        $pdfFilePath = './'. $this->path .$this->filename;

        return $pdfFilePath;
    }
}
