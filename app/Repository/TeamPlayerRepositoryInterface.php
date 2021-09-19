<?php
namespace App\Repository;

interface TeamPlayerRepositoryInterface
{
   public function findByID(int $id);

   public function createorUpdate(array $teamArray, $id);

   public function delete(int $id);

   public function getTeamPlayerByIDOrName($id);

   public function getTeamPlayerListByTeamID($id);
}