<?php

namespace Hcode\Models;

/**
 * @copyright (c) 2017, Francisoco S. Filho SIEWEBDESING
 */
class Pagination {

    private $link;
    private $LinksNavigation;
    private $limit;
    private $maxLinks;
    private $currentPage;
    private $nrRegisters;
    private $totalPages;

    /**
     * Configura a paginação
     * 
     * @param string $link link de onde será feita a paginação
     * @param int $nrRegisters o total de numeros de regitros no banco de dados
     * @param int $currentPage Numero atual da paginação
     * @param int $limit quantos registros serão mostrados por pagina
     * @param int $maxLinks qual a quantidade maxima de links de navegação será mostrado na pagina
     */
    public function __construct($link, $nrRegisters, $currentPage, $limit = 8, $maxLinks = 8) {

        $this->link = (string) $link;
        $this->currentPage = (int) $currentPage;
        $this->limit = (int) $limit;
        $this->maxLinks = (int) $maxLinks;
        $this->nrRegisters = (int) $nrRegisters;

        // Numero total de paginas
        $this->totalPages = ceil($this->nrRegisters / $this->limit);

        //Gera os links da navegação
        $this->setLinksNavigation();
    }

    /**
     * Cria os links da Navegação
     */
    private function setLinksNavigation() {

        //divide para obter a quantidade de links a esquerda e a direita
        $max = ceil($this->maxLinks / 2);

        /*
         * Numero de links a esquerda
         */
        //Calcula o numro de links que talvez deva ser incrementado
        $leftMissing = $this->totalPages - $this->currentPage - $max;
        //Pega o numero de links
        $linksLeft = ( $leftMissing < 0 ? $max + abs($leftMissing) : $max );
        //Previne para não ultrapassar o numero máximo de links
        $linksLeft = ( $linksLeft > $this->totalPages ? $this->totalPages - 1 : $linksLeft );

        /*
         * Numero de links a direita
         */
        //Calcula o numro de links que talvez deva ser incrementado
        $rightMissing = $this->maxLinks - $this->currentPage + 1;
        //Pega o numero de links
        $linksRight = ( $rightMissing > $max ? $rightMissing : $max );   
        
        //MONTA OS LINKS
        $LinksNavigation = [];

        // links à esquerda
        for ($i = $this->currentPage - $linksLeft; $i < $this->currentPage; $i ++):

            if ($i > 0):
                array_push($LinksNavigation, [
                    'href' => $this->link . $i,
                    'text' => $i,
                    'active' => ''
                ]);
            endif;

        endfor;

        // pagina atual
        array_push($LinksNavigation, [
            'href' => $this->link . $this->currentPage,
            'text' => $this->currentPage,
            'active' => 'active'
        ]);

        // links à direita        
        for ($ir = $this->currentPage; $ir <= $this->totalPages; $ir ++):

            if ($ir > $this->currentPage):
                array_push($LinksNavigation, [
                    'href' => $this->link . $ir,
                    'text' => $ir,
                    'active' => ( $this->currentPage == $ir ? 'active' : '' )
                ]);
            endif;

        endfor;

        $this->LinksNavigation = $LinksNavigation;
    }

    public function getLink() {
        return $this->link;
    }

    public function getLinksNavigation() {
        return $this->LinksNavigation;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getMaxLink() {
        return $this->maxLink;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getNumberRegisters() {
        return $this->nrRegisters;
    }

    public function getTotalPages() {
        return $this->totalPages;
    }

}
