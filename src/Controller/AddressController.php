<?php

namespace App\Controller;

use App\Model\AddressManager;

class AddressController extends AbstractController
{
    private $addressManager;

    public function __construct()
    {
        parent::__construct();
        $this->addressManager = new AddressManager();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /user/login');
        }
        $addresses = $this->addressManager->selectAllAddressByUserId($_SESSION['user']['id']);
        return $this->twig->render('address/index.html.twig', ['addresses' => $addresses]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = array_map('trim', $_POST);
            $address['user_id'] = $_SESSION['user']['id'];
            $this->addressManager->insert($address);
            header('Location: /address');
        }
        return $this->twig->render('address/add.html.twig');
    }

    public function edit(int $id)
    {
        $address = $this->addressManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = array_map('trim', $_POST);
            $address['id'] = $id;
            $this->addressManager->update($address);
            header('Location: /address/index');
        }
        return $this->twig->render('Address/edit.html.twig', ['address' => $address]);
    }

    public function delete(int $id)
    {
        $this->addressManager->delete($id);
        header('Location: /address/index');
    }
}
