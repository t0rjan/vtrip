//
//  mlViewEleIconComment.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-21.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewEleIconComment.h"

@implementation mlViewEleIconComment

- (id)initWithFrame:(CGRect)frame
{
    self.type = @"comment";
    self.image = [UIImage imageNamed:@"icon_anno.png"];
    self.imageApplication = [UIImage imageNamed:@"icon_anno.png"];
    
    self = [super initWithFrame:frame];
    if (self) {

    }
    return self;
}

- (void)playSucessAni
{
    self.backgroundColor = [UIColor orangeColor];
}


@end
