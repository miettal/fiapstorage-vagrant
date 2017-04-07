# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.network :public_network
  config.vm.provision "shell",
    run: "always",
    inline: "ip route del default; dhclient enp0s8"

  config.vm.provider "virtualbox" do |vm|
    vm.name = "fiapstorage-vagrant"
    vm.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "fiapstorage-ansible/site.yml"
  end
end
