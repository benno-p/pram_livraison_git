<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!--         <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
        <!-- <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet"> -->
        <title>Fiche numéro {{$fiche->id}}</title>

        <style>
            *{
                margin:0;
                padding:0;
                font-size: 0.80rem;
            }
            .partie-gauche{
                height: 90%;
                width: 49%;
                float: left;
            }
            .partie-droite{
                height: 90%;
                width: 50%;
                float: right;
            }
            .caveat{
/*                font-family: 'Caveat', cursive;*/
            }
            .text-white{
                color: white;
            }
            .text-primary{
                color: #73A0DD;
            }
            .bg-primary{
                background-color: #73A0DD;
            }
            .en-tete{
                text-align:center;
                margin-bottom: 10px;
                padding: 8px;
                background-color: #73A0DD;
            }
            .fw-weight{
                font-weight: bold;
            }
            .text-center{
                text-align: center;
            }
            .mb-05{
                margin-bottom: 5px;
            }
            .mb-1{
                margin-bottom: 10px;
            }
            .mb-2{
                margin-bottom: 20px;
            }
            .bloc-encadrement{
                border: 0.5px solid black;
                width: 100%;
            }
            .text-success{
/*                color: #84B826!important;*/
                color: #215c13!important;
                font-weight: bold;
            }
            .checkbox-height{
                height: 11px;
            }
            .text-secondary{
                color: #6c757d!important;
            }
        </style>
    </head>
    <body>
        <div style="padding: 10px;">
            <div style="width:59%">
                <img src="{{asset('images/logo/logo.png')}}" />
                <h1 class="text-center" style="float: right;font-size: 1.1rem;margin-top: 10px;">Fiche de caractérisation de mare</h1>
            </div>

            <div class="partie-gauche">
                @include('exports.pdf.fiche_pdf_mare_gauche') 
            </div>
            
            <div class="partie-droite">
                @include('exports.pdf.fiche_pdf_mare_droite') 
            </div>
        </div>
    </body>
</html>