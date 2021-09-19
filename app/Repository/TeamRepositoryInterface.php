<?php
namespace App\Repository;

interface TeamRepositoryInterface
{
   public function findByID(int $id);

   public function getTeamList();

   public function createorUpdate(array $teamArray, $id);

   public function delete(int $id);
}