<?php

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    public function getProfile(int $id)
    {
        $data = $this->userRepository->getById($id);

        if (!$data) {
            return null;
        }

        return User::fromArray($data)->toArray();
    }

    public function updateProfile(
        int $id,
        string $firstName,
        string $lastName,
        ?string $avatarPath = null
    ) {
        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
        ];

        if ($avatarPath !== null) {
            $data['avatar'] = $avatarPath;
        }

        return $this->userRepository->update($id, $data);
    }
}
