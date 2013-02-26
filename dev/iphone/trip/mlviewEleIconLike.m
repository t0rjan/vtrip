//
//  mlviewEleIconLike.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-21.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlviewEleIconLike.h"

@implementation mlviewEleIconLike

- (id)initWithFrame:(CGRect)frame
{
    self.type = @"like";
    self.image = [UIImage imageNamed:@"icon_anno.png"];
    self.imageApplication = [UIImage imageNamed:@"icon_anno.png"];
    
    self = [super initWithFrame:frame];
    if (self) {

    }
    return self;
}

- (void)playSucessAni
{
    self.backgroundColor = [UIColor yellowColor];
}

@end
