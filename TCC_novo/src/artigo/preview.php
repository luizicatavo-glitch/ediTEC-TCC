<style>
    #previewSheet { 
        background:#fff; 
        color:#000; 
        
        font-family: Arial, sans-serif;
        
        font-size:12pt; 
        line-height:1.5; 
        
        width: 210mm; 
        min-height: 297mm; 
        
        padding: 3cm 2cm 2cm 3cm; 
        box-sizing:border-box; 
        
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); 
        margin-bottom: 50px;
        
        transform-origin: top center;
        transition: transform 0.3s ease;
    }
    
    @media (max-width: 1450px) {
        #previewSheet {
            transform: scale(0.85); 
            margin-bottom: -10%;
        }
    }
    @media (max-width: 1100px) {
        #previewSheet {
            transform: scale(0.7);
            margin-bottom: -20%;
        }
    }
    @media (max-width: 900px) {
        #previewSheet {
            transform: none;
            width: 100%;
            padding: 2cm 1.5cm;
            min-height: auto;
            margin-bottom: 20px;
        }
    }

    #previewSheet p { margin:0 0 0 0; text-indent:1.25cm; text-align:justify; margin-bottom: 10px; }
    #previewSheet .no-indent p { text-indent:0; }
    #previewSheet h1 { text-align:center; font-size:14pt; text-transform:uppercase; margin-bottom:10px; margin-top:0; font-weight:bold; }
    #previewSheet h3 { font-size:12pt; margin: 18px 0 12px 0; font-weight:bold; text-transform:uppercase; }
    #previewSheet h4.sec-secondary { font-size:12pt; margin:18px 0 12px 0; font-weight:normal; text-transform:uppercase; }
    #previewSheet h5.sec-tertiary { font-size:12pt; margin:18px 0 12px 0; font-weight:bold; }
    
    .citation-block { margin-left:4cm; font-size:10pt; line-height:1.0; text-align:justify; margin-bottom: 12px; }
    .references { margin-top:24px; font-size:12pt; line-height:1.0; text-align:left; }
    .references li { margin-bottom:12px; padding-left:0; text-indent:0; list-style: none; } 

    .page-break { page-break-after:always; break-after:page; border-bottom: 1px dashed #bbb; margin: 20px 0; position: relative; }
    
    @media print {
        #previewSheet { transform: none; margin:0; box-shadow:none; width:100%; }
        .page-break { border:none; }
    }
</style>

<div id="previewSheet" aria-live="polite"></div>