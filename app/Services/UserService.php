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

        $user = User::fromArray($data)->toArray();
        unset($user['password']);
        return $user;
    }

    public function updateProfile(
        int $id,
        string $firstName,
        string $lastName,
        string $phone,
        ?string $avatarPath = null
    ) {
        $data = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'phone'      => $phone,
        ];

        if ($avatarPath !== null) {
            $data['avatar'] = $avatarPath;
        }

        return $this->userRepository->update($id, $data);
    }
}
