<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Promo;

class PromoController extends ControllerBase
{
    
    public function applyAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $used_promos = $this->session->get('discount_codes');

        foreach ($used_promos as $promo) {
            if ($promo == $this->request->getPost('promoInput')) {
                $this->flash->error("Promo code currently being applied.");
                return $this->response->redirect('cart');
            }
        }

        $promo = Promo::findFirstByCode($this->request->getPost('promoInput'));

        if (!$promo) {
            $this->flash->error("Promo code invalid.");
            return $this->response->redirect('cart');
        }

        if ($promo->availability == 0) {
            $this->flash->error("Promo code is no longer available.");
            return $this->response->redirect('cart');
        }

        $user = $this->session->get('user');
        $user_promo_record = $user->PromoRecords;
        if (count($user_promo_record) > 0) {
            foreach ($user_promo_record as $promo_record) {
                $record = Promo::findFirstById($promo_record->promo_id);
                if ($record->code == $this->request->getPost('promoInput')) {
                    $this->flash->error("You have used this promo code before already.");
                    return $this->response->redirect('cart');
                }
            }
        }

        $used_promos[] = $this->request->getPost('promoInput');
        $this->session->set('discount_codes', $used_promos);
        $new_discount_rate = $this->session->get('discount_rate') * $promo->rate;

        $this->session->set('discount_rate', $new_discount_rate);
        return $this->response->redirect('cart');
    }

    public function resetAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("");
        }

        $this->session->set('discount_codes', []);
        $this->session->set('discount_rate', 1);

        return $this->response->redirect('cart');
    }

}
